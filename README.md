# eisbulma
Wordpress Theme using Bulma Css.
- Supports editing with gutenberg blocks (native block stylized with bulmacss)
- Woocommerce ready with floating cart, ajax search, product filters.

Use vite to dev & build for production
1. Use local-wp to run local wordpress site
2. Clone repo to wp-content/theme folder
3. npm init
4. Copy .env.example and replace var (domain, colors, rclone remote path)
5. **npm run dev**
6. Navigate to your local-wp site check console to see if vite is running.
6. **npm run build** to compile assets using vite (just stop vite to see local site using dist assets)

7. **npm run upload** to upload file to remote (rsync)
8. **npm run deploy** to build + upload to remote (rsync)

*TO USE UPLOAD FEATURE :* 
- Install rsync, configure your remote.
- Set your remote path in .env