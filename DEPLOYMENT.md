# Gemini Chat - Deployment Guide

## Local Setup

1. **Install Node.js** (if not already installed)
   - Download from https://nodejs.org/

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Create `.env` file:**
   ```bash
   cp .env.example .env
   ```
   Then edit `.env` and add your Gemini API key:
   ```
   GEMINI_API_KEY=AIzaSyBIo1ZdXa367PRS_O1r4FxwUwlW0XAt0pk
   SESSION_SECRET=your-secret-key-here
   ```

4. **Run locally:**
   ```bash
   npm start
   ```
   Visit `http://localhost:3000`

---

## Deploy to Vercel (Recommended)

1. **Create a GitHub repository:**
   - Go to https://github.com/new
   - Create a new repo called "gemini-chat"
   - Push your code:
     ```bash
     git init
     git add .
     git commit -m "Initial commit"
     git branch -M main
     git remote add origin https://github.com/YOUR_USERNAME/gemini-chat.git
     git push -u origin main
     ```

2. **Deploy on Vercel:**
   - Go to https://vercel.com/new
   - Import your GitHub repository
   - In "Environment Variables", add:
     - `GEMINI_API_KEY` = `AIzaSyBIo1ZdXa367PRS_O1r4FxwUwlW0XAt0pk`
     - `SESSION_SECRET` = `your-secret-key-here`
   - Click "Deploy"

3. **Your app will be live at:** `https://your-project-name.vercel.app`

---

## Deploy to Netlify

1. **Install Netlify CLI:**
   ```bash
   npm install -g netlify-cli
   ```

2. **Create `netlify.toml` file:**
   ```toml
   [build]
     command = "npm install"
     functions = "functions"

   [functions]
     node_bundler = "esbuild"

   [[redirects]]
     from = "/*"
     to = "/.netlify/functions/server"
     status = 200
   ```

3. **Deploy:**
   ```bash
   netlify deploy
   ```

4. **Set environment variables in Netlify dashboard:**
   - Go to Site settings → Build & deploy → Environment
   - Add `GEMINI_API_KEY` and `SESSION_SECRET`

---

## Project Structure

```
gemini-chat/
├── server.js              # Express server
├── package.json           # Dependencies
├── vercel.json            # Vercel config
├── .env.example           # Environment variables template
├── public/
│   └── index.html         # Frontend HTML/CSS/JS
└── README.md              # This file
```

---

## Features

✅ Real-time Gemini AI chat  
✅ Conversation history (stored in session)  
✅ Beautiful chat UI  
✅ Error handling  
✅ Mobile responsive  
✅ Free hosting option (Vercel)  

---

## API Endpoints

- `POST /api/chat` - Send message to Gemini
- `GET /api/history` - Get chat history
- `POST /api/clear` - Clear chat history
- `GET /` - Main page

Enjoy your Gemini Chat! 🚀
