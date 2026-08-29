import makeWASocket, {
    useMultiFileAuthState,
    fetchLatestBaileysVersion,
    proto,
    DisconnectReason
} from "@whiskeysockets/baileys";
import pino from "pino";
import chalk from "chalk";
import readline from "readline";

// Metode Pairing
const usePairingCode = true;

// Prompt Input Terminal
async function question(promt) {
    process.stdout.write(promt);
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout,
    });

    return new Promise((resolve) => rl.question("", (ans) => {
        rl.close();
        resolve(ans);
    }));
}

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('./sijurnalsesion');
    // Versi Terbaru
    const { version, isLatest } = await fetchLatestBaileysVersion();
    console.log(`sijurnal Using WA v${version.join('.')}, isLatest: ${isLatest}`);

    const sijurnal = makeWASocket({
        logger: pino({ level: "silent" }),
        printQRInTerminal: !usePairingCode,
        auth: state,
        browser: ['Ubuntu', 'Chrome', '20.0.04'],
        version: version,
        syncFullHistory: false,
        markOnlineOnConnect: false,
        generateHighQualityLinkPreview: false,
        connectTimeoutMs: 60000,
        keepAliveIntervalMs: 20000,
        retryRequestDelayMs: 500,
        getMessage: async (key) => {
            return proto.Message.fromObject({});
        }
    });

    // Handle Pairing Code
    if (usePairingCode && !sijurnal.authState.creds.registered) {
        try {
            const phoneNumber = await question('☘️ Masukan Nomor Yang Diawali Dengan 62 :\n');
            await sijurnal.waitForSocketOpen();
            const code = await sijurnal.requestPairingCode(phoneNumber.trim());
            console.log(`🎁 Pairing Code : ${code}`);
        } catch (err) {
            console.error('Failed to get pairing code:', err);
        }
    }

    // Menyimpan Sesi Login
    sijurnal.ev.on("creds.update", saveCreds);

    // Informasi Koneksi
    sijurnal.ev.on("connection.update", (update) => {
        const { connection, lastDisconnect } = update;
        if (connection === "close") {
            console.log(chalk.red("❌  Koneksi Terputus, Mencoba Menyambung Ulang"));
            connectToWhatsApp();
        } else if (connection === "open") {
            console.log(chalk.green("✔  sijurnal bot telah aktif"));
        }
    });

    // Respon Pesan Masuk
    sijurnal.ev.on("messages.upsert", async (m) => {
        const msg = m.messages[0];

        if (!msg.message) return;

        // Ambil pesan asli (unwrap ephemeral/viewonce dll)
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

        // Skip pesan sistem WA
        const ignoredTypes = ["protocolMessage", "senderKeyDistributionMessage", "messageContextInfo", "reactionMessage"];
        if (ignoredTypes.includes(messageType)) return;

        // Tentukan isi teks yang ditampilkan
        let body;
        if (messageType === "conversation") {
            body = realMsg.conversation;
        } else if (messageType === "extendedTextMessage") {
            body = realMsg.extendedTextMessage?.text;
        } else if (messageType === "imageMessage") {
            body = chalk.magenta(`[Gambar]`) + (realMsg.imageMessage?.caption ? ` ${realMsg.imageMessage.caption}` : "");
        } else if (messageType === "videoMessage") {
            body = chalk.magenta(`[Video]`) + (realMsg.videoMessage?.caption ? ` ${realMsg.videoMessage.caption}` : "");
        } else if (messageType === "audioMessage") {
            body = realMsg.audioMessage?.ptt ? chalk.magenta("[Voice Note]") : chalk.magenta("[Audio]");
        } else if (messageType === "stickerMessage") {
            body = chalk.magenta("[Stiker]");
        } else if (messageType === "documentMessage") {
            body = chalk.magenta(`[Dokumen]`) + (realMsg.documentMessage?.fileName ? ` ${realMsg.documentMessage.fileName}` : "");
        } else if (messageType === "locationMessage") {
            body = chalk.magenta("[Lokasi]");
        } else if (messageType === "contactMessage") {
            body = chalk.magenta(`[Kontak]`) + (realMsg.contactMessage?.displayName ? ` ${realMsg.contactMessage.displayName}` : "");
        } else {
            body = chalk.gray(`[${messageType}]`);
        }

        const pushname = msg.pushName || msg.key.remoteJid?.split("@")[0] || "sijurnal";

        // Log Pesan Masuk Terminal
        const listColor = ["red", "green", "yellow", "magenta", "cyan", "white", "blue"];
        const randomColor = listColor[Math.floor(Math.random() * listColor.length)];

        console.log(
            chalk.yellow.bold("Credit : SiJurnal"),
            chalk.green.bold("[ WhatsApp ]"),
            chalk[randomColor](pushname),
            chalk[randomColor](" : "),
            chalk.white(body)
        );
    });
}

// Jalankan Koneksi WhatsApp
connectToWhatsApp();
