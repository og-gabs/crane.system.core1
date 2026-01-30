const express = require('express');
const session = require('express-session');
const axios = require('axios');
const path = require('path');
require('dotenv').config();

const app = express();

// Middleware
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(express.static('public'));

app.use(session({
    secret: process.env.SESSION_SECRET || 'your-secret-key',
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false }
}));

// API route for chat
app.post('/api/chat', async (req, res) => {
    try {
        const { prompt } = req.body;

        if (!prompt || !prompt.trim()) {
            return res.status(400).json({ error: 'Prompt is required' });
        }

        // Initialize chat history if not exists
        if (!req.session.chatHistory) {
            req.session.chatHistory = [];
        }

        // Build contents with conversation history
        const contents = [];
        
        // Add previous messages
        if (req.session.chatHistory.length > 0) {
            for (const msg of req.session.chatHistory) {
                contents.push({
                    role: msg.role,
                    parts: [{ text: msg.text }]
                });
            }
        }

        // Add current user message
        contents.push({
            role: "user",
            parts: [{ text: prompt }]
        });

        // Call Gemini API
        const response = await axios.post(
            `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${process.env.GEMINI_API_KEY}`,
            { contents },
            {
                headers: { 'Content-Type': 'application/json' },
                timeout: 30000
            }
        );

        const answer = response.data.candidates?.[0]?.content?.parts?.[0]?.text || 'No response from Gemini.';

        // Save to history
        req.session.chatHistory.push({ role: 'user', text: prompt });
        req.session.chatHistory.push({ role: 'model', text: answer });

        res.json({ answer });

    } catch (error) {
        console.error('Error:', error.message);
        const errorMessage = error.response?.data?.error?.message || error.message || 'An error occurred';
        res.status(500).json({ error: errorMessage });
    }
});

// Route to get chat history
app.get('/api/history', (req, res) => {
    const history = req.session.chatHistory || [];
    res.json({ history });
});

// Route to clear chat history
app.post('/api/clear', (req, res) => {
    req.session.chatHistory = [];
    res.json({ success: true });
});

// Serve main page
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
