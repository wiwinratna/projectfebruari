<?php

namespace App\Http\Controllers;

use App\Models\LandingFooterConfig;
use Illuminate\Http\Request;

class LandingFooterConfigController extends Controller
{
    public function edit()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $config = LandingFooterConfig::query()->firstOrCreate(
            ['key' => 'default'],
            $this->defaultData()
        );

        return view('super-admin.landing-footer.edit', [
            'config' => $config,
            'quickLinksRaw' => $this->linksToRaw($config->quick_links),
            'legalLinksRaw' => $this->linksToRaw($config->legal_links),
        ]);
    }

    public function update(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'brand_description' => 'nullable|string|max:3000',
            'quick_links_title' => 'nullable|string|max:255',
            'connect_title' => 'nullable|string|max:255',
            'quick_links_raw' => 'nullable|string|max:5000',
            'legal_links_raw' => 'nullable|string|max:5000',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'address_text' => 'nullable|string|max:255',
            'address_url' => 'nullable|url|max:255',
            'phone_text' => 'nullable|string|max:255',
            'phone_url' => 'nullable|string|max:255',
            'email_text' => 'nullable|string|max:255',
            'email_url' => 'nullable|string|max:255',
            'copyright_text' => 'nullable|string|max:3000',
        ]);

        $config = LandingFooterConfig::query()->firstOrCreate(['key' => 'default'], $this->defaultData());

        $config->update([
            'brand_description' => $data['brand_description'] ?? null,
            'quick_links_title' => $data['quick_links_title'] ?? null,
            'connect_title' => $data['connect_title'] ?? null,
            'facebook_url' => $data['facebook_url'] ?? null,
            'twitter_url' => $data['twitter_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'address_text' => $data['address_text'] ?? null,
            'address_url' => $data['address_url'] ?? null,
            'phone_text' => $data['phone_text'] ?? null,
            'phone_url' => $data['phone_url'] ?? null,
            'email_text' => $data['email_text'] ?? null,
            'email_url' => $data['email_url'] ?? null,
            'copyright_text' => $data['copyright_text'] ?? null,
        ]);

            if (array_key_exists('quick_links_raw', $data)) {
                $config->quick_links = $this->rawToLinks($data['quick_links_raw'] ?? '');
            }

            if (array_key_exists('legal_links_raw', $data)) {
                $config->legal_links = $this->rawToLinks($data['legal_links_raw'] ?? '');
            }

            $config->save();

        return redirect()->route('super-admin.landing-footer.edit')
            ->with('status', 'Footer content updated successfully!');
    }

    private function rawToLinks(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($raw));

        if (!$lines || (count($lines) === 1 && $lines[0] === '')) {
            return [];
        }

        $links = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 2));
            $label = $parts[0] ?? '';
            $href = $parts[1] ?? '#';

            if ($label === '') {
                continue;
            }

            $links[] = [
                'label' => $label,
                'href' => $href !== '' ? $href : '#',
            ];
        }

        return $links;
    }

    private function linksToRaw(?array $links): string
    {
        if (!$links || !is_array($links)) {
            return '';
        }

        $lines = [];
        foreach ($links as $item) {
            $label = trim((string) ($item['label'] ?? ''));
            $href = trim((string) ($item['href'] ?? '#'));
            if ($label === '') {
                continue;
            }
            $lines[] = $label . ' | ' . ($href !== '' ? $href : '#');
        }

        return implode(PHP_EOL, $lines);
    }

    private function defaultData(): array
    {
        return [
            'brand_description' => 'Revolutionizing sports workforce management through innovative technology and dedicated service.',
            'quick_links_title' => 'Quick Links',
            'connect_title' => 'Connect With Us',
            'quick_links' => [
                ['label' => 'Job Openings', 'href' => '/jobs'],
                ['label' => 'About ARISE', 'href' => '#about'],
                ['label' => 'Our Partners', 'href' => '#our-partners'],
                ['label' => 'Events', 'href' => '#news'],
                ['label' => 'Contact Us', 'href' => '#contact'],
            ],
            'legal_links' => [
                ['label' => 'Privacy', 'href' => '/register'],
                ['label' => 'Terms', 'href' => '/register'],
                ['label' => 'Cookies', 'href' => '/register'],
            ],
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://x.com',
            'instagram_url' => 'https://www.instagram.com/arisegames',
            'linkedin_url' => 'https://linkedin.com',
            'address_text' => 'Jakarta, Indonesia',
            'address_url' => 'https://maps.google.com/?q=Jakarta,Indonesia',
            'phone_text' => '+62 21 1234 5678',
            'phone_url' => 'tel:+622112345678',
            'email_text' => 'info@arise.id',
            'email_url' => 'mailto:info@arise.id',
            'copyright_text' => 'ARISE - National Olympic Academy of Indonesia System. All rights reserved.',
        ];
    }
}
