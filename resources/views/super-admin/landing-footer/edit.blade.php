@extends('layouts.app')

@section('title', 'Footer Content - NOCIS')
@section('page-title')
    Footer Content <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="max-w-5xl space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Footer Content</h2>
        <p class="text-gray-600 mt-1">Ubah isi footer landing page (teks, link, dan kontak).</p>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('super-admin.landing-footer.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="brand_description" class="block text-sm font-medium text-gray-700 mb-1">Brand Description</label>
                <textarea id="brand_description" name="brand_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('brand_description', $config->brand_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="quick_links_title" class="block text-sm font-medium text-gray-700 mb-1">Quick Links Title</label>
                    <input id="quick_links_title" name="quick_links_title" type="text" value="{{ old('quick_links_title', $config->quick_links_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="connect_title" class="block text-sm font-medium text-gray-700 mb-1">Connect Title</label>
                    <input id="connect_title" name="connect_title" type="text" value="{{ old('connect_title', $config->connect_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                    <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $config->facebook_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter/X URL</label>
                    <input id="twitter_url" name="twitter_url" type="url" value="{{ old('twitter_url', $config->twitter_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                    <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $config->instagram_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                    <input id="linkedin_url" name="linkedin_url" type="url" value="{{ old('linkedin_url', $config->linkedin_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="address_text" class="block text-sm font-medium text-gray-700 mb-1">Address Text</label>
                    <input id="address_text" name="address_text" type="text" value="{{ old('address_text', $config->address_text) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="address_url" class="block text-sm font-medium text-gray-700 mb-1">Address URL</label>
                    <input id="address_url" name="address_url" type="url" value="{{ old('address_url', $config->address_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="phone_text" class="block text-sm font-medium text-gray-700 mb-1">Phone Text</label>
                    <input id="phone_text" name="phone_text" type="text" value="{{ old('phone_text', $config->phone_text) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="phone_url" class="block text-sm font-medium text-gray-700 mb-1">Phone URL</label>
                    <input id="phone_url" name="phone_url" type="text" value="{{ old('phone_url', $config->phone_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="email_text" class="block text-sm font-medium text-gray-700 mb-1">Email Text</label>
                    <input id="email_text" name="email_text" type="text" value="{{ old('email_text', $config->email_text) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                    <label for="email_url" class="block text-sm font-medium text-gray-700 mb-1">Email URL</label>
                    <input id="email_url" name="email_url" type="text" value="{{ old('email_url', $config->email_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
            </div>

            <div>
                <label for="copyright_text" class="block text-sm font-medium text-gray-700 mb-1">Copyright Text (without year)</label>
                <textarea id="copyright_text" name="copyright_text" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('copyright_text', $config->copyright_text) }}</textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Footer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
