# Gemini Chat Application

A modern, functional Gemini AI chat application that works on the internet.

## Features

- 💬 Real-time chat with Gemini AI
- 📱 Mobile-responsive design
- 💾 Conversation history in session
- ⚡ Fast and lightweight
- 🔒 Secure API key management
- 🚀 Deploy-ready for Vercel/Netlify

## Quick Start

### Local Development

```bash
npm install
npm start
```

Visit `http://localhost:3000`

### Deploy to Vercel (Free)

1. Push to GitHub
2. Go to https://vercel.com/new
3. Import your repository
4. Add environment variables:
   - `GEMINI_API_KEY` = Your API key
   - `SESSION_SECRET` = Any random string
5. Deploy!

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed instructions.

## Files

- `server.js` - Express.js server
- `public/index.html` - Chat frontend
- `package.json` - Dependencies
- `vercel.json` - Vercel configuration
- `.env` - Environment variables

## API Key

Get your free Gemini API key from: https://aistudio.google.com/app/apikey

## License

MIT
