// Import Module
require('./ndyde')
require('./database/Menu/ndydeasamenu')
const fs = require('fs');

const { Sticker, StickerTypes } = require("wa-sticker-formatter");
const puppeteer = require('puppeteer');
const axios = require('axios');
const {GoogleGenerativeAI} = require ("@google/generative-ai") ;
const genai = new GoogleGenerativeAI ("AIzaSyBi2FZ5V7EqQ0boIhEyHdLz5_WVyPeLzq4");


// Import Scrape
const Ai4Chat = require('./scrape/Ai4Chat');
const tiktok2 = require('./scrape/Tiktok');

async function geminichat (promt) {
    const model = genai.getGenerativeModel ({
        model : "gemini-2.5-pro" 
    });
    const result = await model.generateContent (promt);
    return result.response.text () ;
};

async function getQuotedName(sock, msg) {
  try {
    const quoted = msg.message?.extendedTextMessage?.contextInfo;
    if (!quoted?.participant) return null;

    const jid = quoted.participant;
    const contact = await sock.onWhatsApp(jid);

    return (
      contact?.[0]?.notify ||
      contact?.[0]?.vname ||
      contact?.[0]?.name ||
      
      jid.split('@')[0]
    );
  } catch {
    return null;
  }
}


module.exports = async (ndydeasa, m) => {
    
    const msg = m.messages[0];
    if (!msg.message) return;

    const body = msg.message.conversation || msg.message.extendedTextMessage?.text || "";
    const sender = msg.key.remoteJid;
    const pushname = msg.pushName || "ndydeasa";
    const args = body.slice(1).trim().split(" ");
    const command = args.shift().toLowerCase();
    const q = args.join(" ");

    if (!body.startsWith(prefix)) return;
    ndydeasa.sendPresenceUpdate('composing',sender);
    await new Promise (resolve => setTimeout(resolve, 200));
    const ndydeasareply = (teks) => ndydeasa.sendMessage(sender, { text: teks }, { quoted: msg });
    const isGroup = sender.endsWith('@g.us');
    const isAdmin = (admin.includes(sender));
    const menuImage = fs.readFileSync(image);

    ndydeasa.sendImageAsSticker = async (jid, imageBuffer, quoted, options = {}) => {
    const { Sticker, StickerTypes } = require("wa-sticker-formatter");
    const sticker = new Sticker(imageBuffer, {
        pack: options.packname || "Bot",
        author: options.author || "Bot",
        type: StickerTypes.FULL,
    });
    const stickerBuffer = await sticker.toBuffer();
    await ndydeasa.sendMessage(jid, { sticker: stickerBuffer }, { quoted });

};

async function getQuotedName(sock, msg) {
  try {
    const quoted = msg.message?.extendedTextMessage?.contextInfo;
    if (!quoted?.participant) return null;

    const jid = quoted.participant;
    const contact = await sock.onWhatsApp(jid);

    return (
      contact?.[0]?.notify ||
      contact?.[0]?.vname ||
      contact?.[0]?.name ||
      jid.split('@')[0]
    );
  } catch {
    return null;
  }
}
    


switch (command) {

// Menu
case "menu": {
    await ndydeasa.sendMessage(sender,
        {
            image: menuImage,
            caption: ndydeasamenu,
            mentions: [sender]
        },
    { quoted: msg }
    )
}
break

// Hanya Admin
case "admin": {
    if (!isAdmin) return ndydeasareply(mess.admin); // COntoh Penerapan Hanya Admin
    ndydeasareply("🎁 *Kamu Adalah Admin*"); // Admin Akan Menerima Pesan Ini
}
break

case 'ceknama':
    const name = await getQuotedName(ndydeasa, msg) || pushname || 'Tidak diketahui';
    ndydeasareply(`Nama orang yang di-reply: ${name}`);
    break;


// Hanya Group
case "group": {
    if (!isGroup) return ndydeasareply(mess.group); // Contoh Penerapan Hanya Group
    ndydeasareply("🎁 *Kamu Sedang Berada Di Dalam Grup*"); // Pesan Ini Hanya Akan Dikirim Jika Di Dalam Grup
}
break

//brat bikin sendiri
case "brat": {
    if (!q && (!msg.message.extendedTextMessage || !msg.message.extendedTextMessage.text)) {
        return ndydeasareply(`⚠️ Kirim/reply pesan *${prefix + command}* teksnya`);
    }

    const textInput = q || msg.message.extendedTextMessage.text;

    try {
        // panggil API brat
        const apiUrl = `https://aqul-brat.hf.space/?text=${encodeURIComponent(textInput)}`;

        await ndydeasa.sendImageAsSticker(sender, apiUrl, msg, {
            packname: "NdydeasaBrat",
            author: "Ndydeasa Bot"
        });

    } catch (e) {
        console.error("Error Brat:", e);
        ndydeasareply("⚠️ Server Brat sedang offline!");
    }
}
break

//brat sendiri no api

case "bratlite": {
    if (!q) return ndydeasareply("⚠️ Ketik teks untuk dijadiin stiker!");

    const { Canvas, FontLibrary } = require('skia-canvas');
    const Jimp = require('jimp');
    const path = require('path');

    try {
        const width = 1024;
        const height = 1024;
        const margin = 30; // margin kiri & atas
        const canvas = new Canvas(width, height);
        const ctx = canvas.getContext('2d');

        // Background putih
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, width, height);

        // Daftarkan font lokal
        const fontPath = path.join(__dirname, './lib/arialnarrow.ttf');
        FontLibrary.use('Narrow', fontPath);

        ctx.fillStyle = 'black';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'top';

        const words = q.split(' ');
        let fontSize = 300;
        const minFont = 10;
        const lineSpacing = 1.2;
        let lines = [];

        // Cari fontSize optimal supaya semua kata muat
        while (fontSize >= minFont) {
            lines = [];
            let line = "";

            for (let i = 0; i < words.length; i++) {
                const word = words[i];
                const testLine = line ? line + " " + word : word;

                ctx.font = `${fontSize}px Narrow`;
                const testWidth = ctx.measureText(testLine).width;

                // Kalau kata terakhir di baris melebihi canvas, pindah ke baris baru
                if (testWidth + margin * 2 > width) {
                    if (line) lines.push(line); // simpan baris sekarang
                    line = word; // mulai baris baru
                } else {
                    line = testLine; // tambahkan kata ke baris
                }

                // Kalau kata terlalu panjang sendiri, pecah huruf demi huruf
                if (ctx.measureText(word).width + margin * 2 > width) {
                    let tempWord = "";
                    for (let char of word) {
                        const testChar = tempWord + char;
                        if (ctx.measureText(testChar).width + margin * 2 > width) {
                            if (tempWord) lines.push(tempWord);
                            tempWord = char;
                        } else {
                            tempWord = testChar;
                        }
                    }
                    if (tempWord) line = tempWord;
                }
            }

            if (line) lines.push(line);

            const totalHeight = lines.length * fontSize * lineSpacing;
            if (totalHeight <= height - margin * 2) break;

            fontSize -= 2; // kecilkan font pelan-pelan
        }

        // Gambar teks dari atas
        let y = margin;
        for (let l of lines) {
            ctx.fillText(l, margin, y);
            y += fontSize * lineSpacing;
        }

        // Convert ke buffer dan blur
        const buffer = await canvas.toBuffer('png');
        const image = await Jimp.read(buffer);
        image.blur(4);

        const finalBuffer = await image.getBufferAsync(Jimp.MIME_PNG);

        // Kirim stiker
        await ndydeasa.sendImageAsSticker(sender, finalBuffer, msg, {
            packname: "ndydeasapack",
            author: "bratndydeasa"
        });

    } catch (err) {
        console.error("Gagal bikin stiker:", err);
        ndydeasareply("⚠️ Gagal membuat stiker.");
    }
}
break

// png convert stiker
case "stkr": {
    try {
        let imageBuffer;

        // Ambil gambar dari reply
        if (msg.message.extendedTextMessage?.contextInfo?.quotedMessage?.imageMessage) {
            const quoted = msg.message.extendedTextMessage.contextInfo.quotedMessage.imageMessage;
            const base64 = quoted.imageMessage || quoted.jpegThumbnail;
            imageBuffer = Buffer.from(base64, 'base64');
        } 
        // Atau kirim gambar langsung
        else if (msg.message.imageMessage) {
            const img = msg.message.imageMessage.imageMessage || msg.message.imageMessage.jpegThumbnail;
            imageBuffer = Buffer.from(img, 'base64');
        } else {
            return ndydeasareply("⚠️ Balas atau kirim gambar dulu!");
        }

        const Jimp = require('jimp');
        const image = await Jimp.read(imageBuffer);

        // 1️⃣ Resize tinggi dulu, 1024x1024 (lebih tajam)
        image.contain(1024, 1024, Jimp.HORIZONTAL_ALIGN_CENTER | Jimp.VERTICAL_ALIGN_MIDDLE);

        // 2️⃣ Tambahkan teks jika ada
        if (q) {
            const font = await Jimp.loadFont(Jimp.FONT_SANS_64_BLACK); // font besar
            image.print(
                font,
                0, 0,
                {
                    text: q,
                    alignmentX: Jimp.HORIZONTAL_ALIGN_CENTER,
                    alignmentY: Jimp.VERTICAL_ALIGN_BOTTOM
                },
                1024, 1024
            );
        }

        // 3️⃣ Resize ke 512x512 untuk WA sticker
        image.resize(512, 512, Jimp.RESIZE_BEZIER); // lebih tajam

        const finalBuffer = await image.getBufferAsync(Jimp.MIME_PNG);

        await ndydeasa.sendImageAsSticker(sender, finalBuffer, msg, {
            packname: "StikerGrafikKartel",
            author: "Ndydeasa Bot"
        });

    } catch (err) {
        console.error(err);
        ndydeasareply("⚠️ Gagal membuat stiker.");
    }
}
break

// hidden tag
case "htag": {
    try {
        const metadata = await ndydeasa.groupMetadata(sender);
        const participants = metadata.participants.map(p => p.id);

        await ndydeasa.sendMessage(
            sender,
            { text: "p", 
              mentions: participants
            }
        );
    } catch (e) {
        console.error(e);
        
        
    }
}
break

// AI Chat
case "ai": {
    if (!q) return ndydeasareply("☘️ *Contoh:* /ai Apa itu JavaScript?");

    try {
        const fanai = await Ai4Chat(q);
            await ndydeasareply(`*Ndydeasaa AI*\n\n${fanai}`);
                } catch (error) {
            console.error("Error:", error);
        ndydeasareply(mess.error);
    }
}
break;

//gemini chat
case "gemini": {
    if (!q) return ndydeasareply("☘ Contoh: /gemini Apa itu JavaScript?");

    try {
        const geminiai = await geminichat(q);
        await ndydeasareply(`*🤖 Ndydeasaa AI Gemini*\n\n${geminiai}`);
    } catch (error) {
        console.error("Error Gemini:", error);
        ndydeasareply("⚠ Terjadi kesalahan saat menghubungi Gemini API.");
    }
}
break;

//vt download
case "ttdl": {
    if (!q) return ndydeasareply("⚠ *Mana Link Tiktoknya?*");
        ndydeasareply(mess.wait);
    try {
        const result = await tiktok2(q); // Panggil Fungsi Scraper
        
            // Kirim Video
            await ndydeasa.sendMessage(
                sender,
                    {
                        video: { url: result.no_watermark },
                        caption: `*🎁 vt success*`
                    },
                { quoted: msg }
            );

        } catch (error) {
            console.error("Error TikTok DL:", error);
        ndydeasareply(mess.error);
    }
}
break;

case 'qcg': {
    if (!q) return ndydeasareply('⚠️ Teksnya mana?');
    if (q.length > 10000) return ndydeasareply("⚠️ Maximal 10000 karakter!");

    const FormData = require("form-data");
    const { fromBuffer } = require("file-type");

    const getBuffer = async (url) => {
        const res = await axios.get(url, { responseType: "arraybuffer" });
        return Buffer.from(res.data);
    };

    // Hapus emoji biar API gak error
    function removeEmojis(str) {
        return str.replace(
            /([\u2700-\u27BF]|[\uE000-\uF8FF]|[\uD83C-\uDBFF\uDC00-\uDFFF]|\uD83D[\uDC00-\uDE4F]|\uD83D[\uDE80-\uDEFF])/g,
            ''
        );
    }

    async function uploadCatbox(buffer) {
        try {
            const form = new FormData();
            const { ext } = await fromBuffer(buffer);
            form.append("fileToUpload", buffer, "file." + ext);
            form.append("reqtype", "fileupload");
            const res = await axios.post("https://catbox.moe/user/api.php", form, {
                headers: form.getHeaders(),
            });
            if (res.data && res.data.startsWith("https://")) return res.data;
            return null;
        } catch {
            return null;
        }
    }

    try {
        const defaultProfile = "https://raw.githubusercontent.com/AhmadAkbarID/media/refs/heads/main/kontak.jpg";
        let finalUrl = defaultProfile;

try {
    // Tentukan senderJid dengan benar
    const senderJid = isGroup
        ? msg.key.participant       // di grup, ambil participant
        : msg.key.remoteJid;        // di chat pribadi, ambil remoteJid

    const profilePic = await ndydeasa.profilePictureUrl(senderJid, "image").catch(() => null);

    if (profilePic) {
        const buffer = await getBuffer(profilePic);
        const uploaded = await uploadCatbox(buffer);
        if (uploaded) finalUrl = uploaded;
    }
} catch (e) {
    console.log("Gagal ambil profil sender:", e);
}

const cleanName = pushname || "User";



        const payload = {
            messages: [
                {
                    from: {
                        id: 1,
                        name: cleanName,
                        photo: { url: finalUrl },
                    },
                    text: q,
                    avatar: true,
                }
            ],
            backgroundColor: "#292232",
            width: 512,
            height: 512,
            scale: 2,
            type: "quote",
            format: "png",
            emojiStyle: "apple"
        };

        const res = await axios.post("https://brat.siputzx.my.id/quoted", payload, {
            responseType: 'arraybuffer',
            headers: { "Content-Type": "application/json" }
        });

        await ndydeasa.sendImageAsSticker(sender, res.data, msg, {
            packname: "NdydeasaBot QC",
            author: "Ndydeasa",
        });

    } catch (err) {
        console.error("Error QC:", err);
        ndydeasareply("❌ Gagal membuat quote.");
    }
}
break;

case 'qcp': {
    if (!q) return ndydeasareply('⚠️ Teksnya mana?');
    if (q.length > 10000) return ndydeasareply("⚠️ Maximal 10000 karakter!");

    const FormData = require("form-data");
    const { fromBuffer } = require("file-type");

    const getBuffer = async (url) => {
        const res = await axios.get(url, { responseType: "arraybuffer" });
        return Buffer.from(res.data);
    };

    // Hapus emoji biar API gak error
    function removeEmojis(str) {
        return str.replace(
            /([\u2700-\u27BF]|[\uE000-\uF8FF]|[\uD83C-\uDBFF\uDC00-\uDFFF]|\uD83D[\uDC00-\uDE4F]|\uD83D[\uDE80-\uDEFF])/g,
            ''
        );
    }

    async function uploadCatbox(buffer) {
        try {
            const form = new FormData();
            const { ext } = await fromBuffer(buffer);
            form.append("fileToUpload", buffer, "file." + ext);
            form.append("reqtype", "fileupload");
            const res = await axios.post("https://catbox.moe/user/api.php", form, {
                headers: form.getHeaders(),
            });
            if (res.data && res.data.startsWith("https://")) return res.data;
            return null;
        } catch {
            return null;
        }
    }

    try {
        const defaultProfile = "https://raw.githubusercontent.com/AhmadAkbarID/media/refs/heads/main/kontak.jpg";
        let finalUrl = defaultProfile;

try {
    // sender yang mengirim perintah /qc
    const senderJid = msg.key.fromMe ? ndydeasa.user.id : msg.key.remoteJid;
    const profilePic = await ndydeasa.profilePictureUrl(senderJid, "image").catch(() => null);
    
    if (profilePic) {
        const buffer = await getBuffer(profilePic);
        const uploaded = await uploadCatbox(buffer);
        if (uploaded) finalUrl = uploaded;
    }
} catch (e) {
    console.log("Gagal ambil profil sender:", e);
}

const cleanName = pushname || "User";



        const payload = {
            messages: [
                {
                    from: {
                        id: 1,
                        name: cleanName,
                        photo: { url: finalUrl },
                    },
                    text: q,
                    avatar: true,
                }
            ],
            backgroundColor: "#292232",
            width: 512,
            height: 512,
            scale: 2,
            type: "quote",
            format: "png",
            emojiStyle: "apple"
        };

        const res = await axios.post("https://brat.siputzx.my.id/quoted", payload, {
            responseType: 'arraybuffer',
            headers: { "Content-Type": "application/json" }
        });

        await ndydeasa.sendImageAsSticker(sender, res.data, msg, {
            packname: "NdydeasaBot QC",
            author: "Ndydeasa",
        });

    } catch (err) {
        console.error("Error QC:", err);
        ndydeasareply("❌ Gagal membuat quote.");
    }
}
break;

//iphone chat
case 'iqc': {
  if (!q) return ndydeasareply("⚠ Contoh: /iqc Halo bang | 90 | 3 | 22:00 ( teks | batrai hp | sinyal | waktu [opsional])");

  let parts = q.split("|").map(s => s.trim());
  let pesan = parts[0];
  let baterai = 3, sinyal = 3, jam;

  if (parts.length === 2) jam = parts[1];
  else if (parts.length === 3) {
    baterai = parseInt(parts[1]) || 3;
    sinyal = parseInt(parts[2]) || 3;
  } else if (parts.length === 4) {
    baterai = parseInt(parts[1]) || 3;
    sinyal = parseInt(parts[2]) || 3;
    jam = parts[3];
  }

  baterai = Math.min(Math.max(baterai, 0), 100);
  sinyal = Math.min(Math.max(sinyal, 1), 4);

  if (!jam) {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, "0");
    const m = String(now.getMinutes()).padStart(2, "0");
    jam = `${h}:${m}`;
  }

  const apiUrl = `https://brat.siputzx.my.id/iphone-quoted?messageText=${encodeURIComponent(pesan)}&carrierName=TELKOMSEL&batteryPercentage=${baterai}&signalStrength=${sinyal}&time=${encodeURIComponent(jam)}`;

  await ndydeasa.sendPresenceUpdate('composing',msg.key.remoteJid);
  await ndydeasa.sendMessage(msg.key.remoteJid, { image: { url: apiUrl } }, { quoted: msg });
}
break;

//ig download
case "igdl": {
    if (!q) return ndydeasareply("⚠ *Mana Link Instagramnya?*");
    try {
        ndydeasareply(mess.wait);

        // Panggil API Velyn
        const apiUrl = `https://www.velyn.biz.id/api/downloader/instagram?url=${encodeURIComponent(q)}`;
        const response = await axios.get(apiUrl);

        if (!response.data.status || !response.data.data.url[0]) {
            throw new Error("Link tidak valid atau API error");
        }

        const data = response.data.data;
        const mediaUrl = data.url[0];
        const metadata = data.metadata;

        // Kirim Media
        if (metadata.isVideo) {
            await ndydeasa.sendMessage(
                sender,
                    {
                        video: { url: mediaUrl },
                        caption: `*Instagram Reel*\n\n` +
                            `*Username :* ${metadata.username}\n` +
                            `*Likes :* ${metadata.like.toLocaleString()}\n` +
                            `*Comments :* ${metadata.comment.toLocaleString()}\n\n` +
                            `*Caption :* ${metadata.caption || '-'}\n\n` +
                            `*Source :* ${q}`
                    },
                    { quoted: msg }
                );
        } else {
            await ndydeasa.sendMessage(
                sender,
                    {
                        image: { url: mediaUrl },
                        caption: `*Instagram Post*\n\n` +
                            `*Username :* ${metadata.username}\n` +
                            `*Likes :* ${metadata.like.toLocaleString()}\n\n` +
                            `*Caption :* ${metadata.caption || '-'}`
                    },
                    { quoted: msg }
                );
            }

        } catch (error) {
            console.error("Error Instagram DL:", error);
        ndydeasareply(mess.error);
    }
}
break;

// Game Tebak Angka
case "tebakangka": {
    const target = Math.floor(Math.random() * 100);
        ndydeasa.tebakGame = { target, sender };
    ndydeasareply("*Tebak Angka 1 - 100*\n*Ketik !tebak [Angka]*");
}
break;

//tebak angka
case "tebak": {
    if (!ndydeasa.tebakGame || ndydeasa.tebakGame.sender !== sender) return;
        const guess = parseInt(args[0]);
    if (isNaN(guess)) return ndydeasareply("❌ *Masukkan Angka!*");

    if (guess === ndydeasa.tebakGame.target) {
        ndydeasareply(`🎉 *Tebakkan Kamu Benar!*`);
            delete ndydeasa.tebakGame;
        } else {
            ndydeasareply(guess > ndydeasa.tebakGame.target ? "*Terlalu Tinggi!*" : "*Terlalu rendah!*");
    }
}
break;

case "quote": {
    const quotes = [
        "Jangan menyerah, hari buruk akan berlalu.",
        "Kesempatan tidak datang dua kali.",
        "Kamu lebih kuat dari yang kamu kira.",
        "Hidup ini singkat, jangan sia-siakan."
    ];
    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
    ndydeasareply(`*Quote Hari Ini :*\n_"${randomQuote}"_`);
}
break;

        default: { console.log(mess.default) }
    }
}

