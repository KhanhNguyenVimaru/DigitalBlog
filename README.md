# Digital Blog

**Digital Blog** is a blogging platform that allows users to easily create, share, and interact with posts.  
Built with **Laravel** for the backend, **Tailwind CSS** for the UI, **MySQL** for the database, and **JavaScript** (Cropper.js, SweetAlert2, Editor.js) to enhance user experience.

## Main Features
- **Account**: Register, login, email verification, change password, update profile, update avatar, delete account.
- **Posts**: Create, update status, delete, upload files, view posts by category or author, fetch all posts via API.
- **Comments & Likes**: Add/remove comments, like/unlike posts, count likes.
- **Follow System**: Follow/unfollow users, accept/deny follow requests, ban/unban users, view followers/following lists.
- **Notifications & Search**: Receive/delete notifications, search suggestions, advanced search, preview links when creating posts.
- **Security**: `auth` middleware protects important routes.

## System Requirements
- PHP 8.x, Laravel 12.x
- MySQL 8.x
- Node.js & npm (for asset building)
