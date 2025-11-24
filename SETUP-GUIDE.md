# NephSpace Elite Construction - Quick Setup Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Verify Files
Ensure you have these files in your project:
```
✓ index.html
✓ about.html
✓ contact.php
✓ css/style.css
✓ js/main.js
✓ assets/img/ (folder)
```

### Step 2: Add Images
1. Download construction/architecture images from:
   - Unsplash.com
   - Pexels.com
   - Or use your own photos

2. Save them in `assets/img/` with these names:
   - hero1.jpg, hero2.jpg, hero3.jpg (1920x1080px)
   - about.jpg (800x600px)
   - project1.jpg through project6.jpg (600x400px)
   - client1.jpg, client2.jpg (100x100px)

### Step 3: Configure Contact Form
1. Open `contact.php`
2. Line 60: Change email to yours:
   ```php
   $to = 'your-email@example.com';
   ```
3. Lines 74, 121: Update sender email if needed

### Step 4: Update Company Info
Edit `index.html` and search for:
- Phone: `+254 700 000 000`
- Email: `info@nephspace.co.ke`
- Address: `Nairobi, Kenya`

Replace with your actual details.

### Step 5: Test Locally
1. **Using XAMPP:**
   - Copy folder to `C:\xampp\htdocs\`
   - Start Apache in XAMPP Control Panel
   - Visit: `http://localhost/NephSpace Elite Construction/`

2. **Using Live Server (VS Code):**
   - Install "Live Server" extension
   - Right-click `index.html` → "Open with Live Server"
   - Note: Contact form won't work without PHP server

### Step 6: Deploy to Live Server
1. Upload all files via FTP/cPanel
2. Ensure PHP is enabled
3. Test contact form
4. Check all images load correctly

## ✅ Checklist

- [ ] All files uploaded
- [ ] Images added to assets/img/
- [ ] Contact email configured
- [ ] Company info updated
- [ ] Tested on local server
- [ ] Contact form working
- [ ] Mobile responsive checked
- [ ] All links working

## 🎨 Customization

### Change Colors
Edit `css/style.css` lines 7-18:
```css
--primary-color: #d4a574;  /* Gold/Bronze */
--secondary-color: #2c3e50; /* Dark Blue */
--accent-color: #c89860;    /* Light Gold */
```

### Change Fonts
Edit `index.html` line 17 (Google Fonts link)

### Add More Services
Copy a service card in `index.html` and modify content

## 🔧 Troubleshooting

**Contact form not working?**
- Ensure PHP is enabled on server
- Check email configuration in contact.php
- Verify server can send emails (some hosts block mail())

**Images not showing?**
- Check file names match exactly (case-sensitive)
- Verify images are in assets/img/ folder
- Check file extensions (.jpg not .jpeg)

**Layout broken on mobile?**
- Clear browser cache
- Check Bootstrap CSS is loading
- Verify viewport meta tag is present

## 📱 Testing Checklist

Test on:
- [ ] Desktop (Chrome, Firefox, Safari, Edge)
- [ ] Tablet (iPad, Android tablet)
- [ ] Mobile (iPhone, Android phone)
- [ ] Different screen sizes

## 🌐 SEO Optimization

1. Update meta descriptions in each HTML file
2. Add relevant keywords
3. Optimize image file sizes (use TinyPNG.com)
4. Add alt text to all images
5. Submit sitemap to Google Search Console

## 📞 Support

For issues or questions:
- Check README.md for detailed documentation
- Review code comments in files
- Test in different browsers

## 🎯 Next Steps

1. Create services.html and projects.html pages
2. Add more project portfolio items
3. Integrate Google Analytics
4. Add Google Maps to contact section
5. Set up email marketing integration
6. Add blog section (optional)

---

**Remember:** This is a professional website template. Customize it to match your brand and business needs!

Good luck with your website! 🚀

