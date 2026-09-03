import makeWASocket, {
    useMultiFileAuthState,
    fetchLatestBaileysVersion,
    proto,
    DisconnectReason
} from "@whiskeysockets/baileys";
import pino from "pino";
import chalk from "chalk";
import express from "express";
import QRCode from "qrcode";
import fs from "fs";
import path from "path";

// Express Server Setup
const app = express();
const PORT = process.env.PORT || 3000;
app.use(express.json());

// Bot State Management
let sijurnal = null;
let botStatus = "disconnected"; // 'disconnected' | 'connecting' | 'qr_ready' | 'connected'
let qrCodeData = null; // DataURL PNG string
let rawQr = null;
let pairingCodeData = null;
let connectedUser = null;
let startTime = new Date();

// Helper Format Phone Number (e.g. 0812... -> 62812... @s.whatsapp.net)
function formatJid(phone) {
    if (!phone) return null;
    let clean = phone.replace(/[^0-9]/g, "");
    if (clean.startsWith("0")) {
        clean = "62" + clean.slice(1);
    }
    if (!clean.endsWith("@s.whatsapp.net")) {
        clean += "@s.whatsapp.net";
    }
    return clean;
}

async function connectToWhatsApp() {
    botStatus = "connecting";
    const { state, saveCreds } = await useMultiFileAuthState('./sijurnalsesion');
    const { version, isLatest } = await fetchLatestBaileysVersion();
    console.log(chalk.cyan(`sijurnal Using WA v${version.join('.')}, isLatest: ${isLatest}`));

    sijurnal = makeWASocket({
        logger: pino({ level: "silent" }),
        auth: state,
        browser: ['Ubuntu', 'Chrome', '20.0.04'],
        version: version,
        syncFullHistory: false,
        markOnlineOnConnect: true,
        generateHighQualityLinkPreview: false,
        connectTimeoutMs: 60000,
        keepAliveIntervalMs: 20000,
        retryRequestDelayMs: 500,
        getMessage: async (key) => {
            return proto.Message.fromObject({});
        }
    });

    sijurnal.ev.on("creds.update", saveCreds);

    sijurnal.ev.on("connection.update", async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            botStatus = "qr_ready";
            rawQr = qr;
            try {
                qrCodeData = await QRCode.toDataURL(qr);
            } catch (err) {
                console.error("QR Code generation error:", err);
            }
            console.log(chalk.yellow("[WA BOT] QR Code baru siap di-scan"));
        }

        if (connection === "close") {
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;
            botStatus = "disconnected";
            qrCodeData = null;
            pairingCodeData = null;
            connectedUser = null;
            
            console.log(chalk.red(`[WA BOT] Koneksi Terputus (Status: ${statusCode}). Mencoba Ulang: ${shouldReconnect}`));
            if (shouldReconnect) {
                setTimeout(connectToWhatsApp, 3000);
            }
        } else if (connection === "open") {
            botStatus = "connected";
            qrCodeData = null;
            pairingCodeData = null;
            connectedUser = {
                id: sijurnal.user?.id ? sijurnal.user.id.split(':')[0] : '',
                name: sijurnal.user?.name || sijurnal.user?.notify || 'SiJurnal Bot',
            };
            console.log(chalk.green("[WA BOT] sijurnal bot telah aktif & terhubung!"));
        }
    });

    // Respon Pesan Masuk
    sijurnal.ev.on("messages.upsert", async (m) => {
        const msg = m.messages[0];
        if (!msg || !msg.message) return;

        function getRealMessage(message) {
            if (!message) return null;
            if (message.ephemeralMessage) return getRealMessage(message.ephemeralMessage.message);
            if (message.viewOnceMessage) return getRealMessage(message.viewOnceMessage.message);
            if (message.viewOnceMessageV2) return getRealMessage(message.viewOnceMessageV2.message);
            if (message.documentWithCaptionMessage) return getRealMessage(message.documentWithCaptionMessage.message);
            return message;
        }

        const realMsg = getRealMessage(msg.message);
        if (!realMsg) return;

        const messageType = Object.keys(realMsg)[0];
        const ignoredTypes = ["protocolMessage", "senderKeyDistributionMessage", "messageContextInfo", "reactionMessage"];
        if (ignoredTypes.includes(messageType)) return;

        let body = "";
        if (messageType === "conversation") body = realMsg.conversation;
        else if (messageType === "extendedTextMessage") body = realMsg.extendedTextMessage?.text;
        else body = `[${messageType}]`;

        const pushname = msg.pushName || msg.key.remoteJid?.split("@")[0] || "sijurnal";
        console.log(chalk.yellow("[ WhatsApp ]"), chalk.cyan(pushname), ":", chalk.white(body));
    });
}

// REST API Endpoints for Laravel Integration
app.get("/api/status", (req, res) => {
    return res.json({
        success: true,
        status: botStatus, // 'disconnected' | 'connecting' | 'qr_ready' | 'connected'
        user: connectedUser,
        qrCode: qrCodeData,
        pairingCode: pairingCodeData,
        uptimeSeconds: Math.floor((new Date() - startTime) / 1000)
    });
});

app.post("/api/pair-code", async (req, res) => {
    try {
        const { phoneNumber } = req.body;
        if (!phoneNumber) {
            return res.status(400).json({ success: false, message: "Nomor WhatsApp wajib diisi" });
        }

        if (botStatus === "connected") {
            return res.status(400).json({ success: false, message: "Bot sudah terhubung!" });
        }

        if (!sijurnal) {
            return res.status(500).json({ success: false, message: "Bot belum siap" });
        }

        let cleanPhone = phoneNumber.replace(/[^0-9]/g, "");
        if (cleanPhone.startsWith("0")) {
            cleanPhone = "62" + cleanPhone.slice(1);
        }

        await sijurnal.waitForSocketOpen();
        const code = await sijurnal.requestPairingCode(cleanPhone);
        pairingCodeData = code;
        console.log(chalk.green(`🎁 [WA BOT] Pairing Code Dibuat: ${code}`));

        return res.json({
            success: true,
            code: code,
            phoneNumber: cleanPhone
        });
    } catch (err) {
        console.error("Pairing code error:", err);
        return res.status(500).json({ success: false, message: err.message || "Gagal membuat pairing code" });
    }
});

app.post("/api/send", async (req, res) => {
    try {
        const { phone, message } = req.body;
        if (!phone || !message) {
            return res.status(400).json({ success: false, message: "Nomor penerima dan pesan wajib diisi" });
        }

        if (botStatus !== "connected" || !sijurnal) {
            return res.status(400).json({ success: false, message: "Bot WhatsApp belum terhubung" });
        }

        const jid = formatJid(phone);
        const result = await sijurnal.sendMessage(jid, { text: message });

        return res.json({
            success: true,
            message: "Pesan berhasil dikirim",
            details: result
        });
    } catch (err) {
        console.error("Send message error:", err);
        return res.status(500).json({ success: false, message: err.message || "Gagal mengirim pesan" });
    }
});

app.post("/api/logout", async (req, res) => {
    try {
        if (sijurnal) {
            await sijurnal.logout();
        }
        const sessionPath = './sijurnalsesion';
        if (fs.existsSync(sessionPath)) {
            fs.rmSync(sessionPath, { recursive: true, force: true });
        }
        botStatus = "disconnected";
        qrCodeData = null;
        pairingCodeData = null;
        connectedUser = null;

        setTimeout(connectToWhatsApp, 2000);

        return res.json({
            success: true,
            message: "Bot berhasil di-logout dan sesi dibersihkan"
        });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message || "Gagal logout bot" });
    }
});

app.post("/api/reconnect", async (req, res) => {
    try {
        connectToWhatsApp();
        return res.json({ success: true, message: "Inisialisasi ulang koneksi bot..." });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// Start Express Server & Initial Connect
app.listen(PORT, () => {
    console.log(chalk.blue.bold(`🚀 [WA BOT API] Server listening on http://127.0.0.1:${PORT}`));
    connectToWhatsApp();
});
