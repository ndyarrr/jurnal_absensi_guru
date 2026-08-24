const axios = require('axios');

    async function Ai4Chat(prompt) {
        const url = new URL("https://yw85opafq6.execute-api.us-east-1.amazonaws.com/default/boss_mode_15aug");
        url.search = new URLSearchParams({
            text: prompt,
            country: "Europe",
            user_id: "Av0SkyG00D" // Thanks To Avosky
        }).toString();

        try {
            const response = await axios.get(url.toString(), {
                headers: {
                    "User-Agent": "Mozilla/5.0 (Linux; Android 11; Infinix) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.0.0 Mobile Safari/537.36",
                    Referer: "https://www.ai4chat.co/pages/riddle-generator"
                }
            });

            if (response.status !== 200) {
                throw new Error(`Error: ${response.status}`);
            }

            return response.data;
        } catch (error) {
            console.error("Fetch error:", error.message);
            throw error;
        }
    }

module.exports = Ai4Chat;