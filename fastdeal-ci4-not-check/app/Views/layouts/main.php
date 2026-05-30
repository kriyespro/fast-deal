<?php
$settingModel = new \App\Models\SettingModel();
$settings = $settingModel->getAllAsMap();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->renderSection('meta_desc') ?: esc($settings['meta_description'] ?? 'FastDeal Real Estate') ?>">
    <title>
        <?= $this->renderSection('title') ?: esc($settings['site_name'] ?? 'FastDeal') ?>
    </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --color-bg: #f8fafc;
            --color-red: #bb2821;
            --color-surface: #ffffff;
            --color-surface-hover: #f1f5f9;
            --color-primary: #1a2035;
            --color-primary-light: #2e354a;
            --color-accent: #c8a14b;
            --color-text: #0f172a;
            --color-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        <?= $this->renderSection('extra_css') ?>

        .glass-panel {
            background: rgba(22, 27, 34, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(135deg, #c8a14b 0%, #e8d087 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient-old {
            background: linear-gradient(135deg, #c9a84c 0%, #e8d087 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .image-overlay {
            background: linear-gradient(to top, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0.4) 50%, rgba(248, 250, 252, 0.1) 100%);
        }

        .hover-lift {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#0f172a',
                        paper: '#f8fafc',
                        surface: '#ffffff',
                        primary: '#1a2035',
                        red: '#bb2821',
                        accent: '#c8a14b',
                        muted: '#8b949e'
                    }
                }
            }
        }
    </script>
</head>

<body class="antialiased overflow-x-hidden <?= $this->renderSection('body_classes') ?? 'flex flex-col min-h-screen' ?>"
    x-data="{ mobileMenuOpen: false }" <?= $this->renderSection('body_attributes') ?>>

    <!-- Navigation -->
    <?= $this->include('partials/nav') ?>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <?php 
        $footerContent = $this->renderSection('footer');
        echo !empty($footerContent) ? $footerContent : $this->include('partials/footer');
    ?>

    <!-- Floating WhatsApp Button -->
    <?php if (isset($settings['site_whatsapp']) && !empty($settings['site_whatsapp'])): ?>
    <a href="https://wa.me/<?= esc($settings['site_whatsapp']) ?>" target="_blank" rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-3.5 rounded-full shadow-lg shadow-[#25D366]/30 hover:bg-[#1EBE5D] hover:scale-110 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center group">
        <i class="ph-fill ph-whatsapp-logo text-3xl"></i>
    </a>
    <?php endif; ?>

</body>

</html>