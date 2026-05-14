<p align="center">
  <img src="public/images/banner.png" width="100%" alt="LaraNews Banner">
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://img.shields.io/badge/Laravel-12.x-red?style=flat-square&logo=laravel" alt="Laravel Version"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php" alt="PHP Version"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License"></a>
  <a href="#"><img src="https://img.shields.io/badge/Status-Active-success?style=flat-square" alt="Status"></a>
</p>

# 📰 LaraNews - Modern News & Magazine Platform

LaraNews is a powerful, open-source news and magazine publishing platform built with the latest **Laravel 12**, **Livewire 3**, and **Tailwind CSS**. Designed for high performance, SEO excellence, and extreme flexibility, it empowers creators to build professional media outlets with ease.

---

## ✨ Key Features

### 🚀 Core Publishing
- **Dynamic Content Management**: Easily manage posts, categories, and tags with a sleek UI.
- **AI-Powered Workflows**: Integrated with OpenAI for automated summaries, content generation, and smart editing.
- **Multi-Language Support**: Fully translatable content system (Spatie Translatable).
- **RSS Feeds & Sitemaps**: Automated SEO-friendly feeds and sitemaps out of the box.

### 👥 Engagement & Community
- **Advanced Comment System**: Nested comments for better reader interaction.
- **Social Integration**: Social login (Socialite) and easy sharing options.
- **Polls & Quizzes**: Interactive tools to boost reader engagement.
- **Newsletter Management**: Built-in newsletter subscription system.

### 💰 Monetization & Growth
- **Membership & Subscriptions**: Integrated with **Stripe** for paid content and premium memberships.
- **Ads Management**: Sophisticated ad placement and tracking system.
- **Analytics**: Built-in analytics to track performance and reader behavior.

### 🛠️ Technical Excellence
- **Meilisearch Integration**: Lightning-fast search powered by Laravel Scout.
- **Media Library**: Robust media handling with Spatie MediaLibrary.
- **Role-Based Access (RBAC)**: Fine-grained permissions using Spatie Permissions.
- **Security First**: Support for Passkeys and secure authentication via Jetstream.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend**: [Livewire 3](https://livewire.laravel.com), [Alpine.js](https://alpinejs.dev), [Tailwind CSS](https://tailwindcss.com)
- **Build Tool**: [Vite](https://vitejs.dev)
- **Database**: MySQL / PostgreSQL / SQLite
- **Search Engine**: [Meilisearch](https://www.meilisearch.com)
- **Queue/Background Jobs**: [Laravel Horizon](https://laravel.com/docs/horizon)

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or any supported database

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/siyamex/LaraNews.git
   cd LaraNews
   ```

2. **Run the setup command**
   We've included a convenient setup script to handle everything:
   ```bash
   composer run setup
   ```
   *This command will install dependencies, generate your app key, run migrations, and build your assets.*

3. **Configure your environment**
   Edit the `.env` file and set your database, Stripe, and OpenAI credentials:
   ```bash
   DB_DATABASE=laranews
   OPENAI_API_KEY=your_key_here
   STRIPE_KEY=your_key_here
   ```

4. **Start the development server**
   ```bash
   composer run dev
   ```
   Visit `http://localhost:8000` to see your platform in action!

---

## 🤝 Contributing

LaraNews is an open-source project and we love contributions! Whether it's a bug fix, a new feature, or documentation improvements, feel free to open a Pull Request.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<p align="center">Built with ❤️ for the publishing community.</p>
