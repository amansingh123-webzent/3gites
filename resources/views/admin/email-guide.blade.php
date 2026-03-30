@extends('layouts.app')
@section('title', 'Email Setup Guide')
@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-600">Admin</a>
        <span class="mx-2">›</span>
        <span class="text-slate-800">Email Setup Guide</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-navy-900 px-8 py-6">
            <h1 class="font-playfair text-2xl font-bold text-white">@3gites.org Email Setup</h1>
            <p class="text-slate-400 text-sm mt-1">
                How to give each member a personal <code class="text-gold-400">name@3gites.org</code> address
            </p>
        </div>

        <div class="px-8 py-8 space-y-8 text-sm text-slate-700 leading-relaxed">

            <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 text-blue-800">
                <strong>Note:</strong> Member email addresses are managed externally via Google Workspace
                or Zoho Mail. This is separate from the Laravel application. The steps below are
                one-time setup performed by the domain administrator.
            </div>

            {{-- Option A: Google Workspace --}}
            <div>
                <h2 class="font-playfair text-xl font-bold text-navy-900 mb-4">Option A — Google Workspace</h2>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</div>
                        <div>
                            <p class="font-semibold text-slate-800">Sign up for Google Workspace</p>
                            <p class="text-slate-500">Go to <a href="https://workspace.google.com" target="_blank" class="text-gold-600 underline">workspace.google.com</a> and start a Business Starter plan (~$6/user/month). You only need it for the users who will actually use the <code>@3gites.org</code> mailbox.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</div>
                        <div>
                            <p class="font-semibold text-slate-800">Verify domain ownership</p>
                            <p class="text-slate-500">Google will give you a TXT record to add to your DNS. In Hostinger's control panel go to <strong>DNS Zone Editor</strong> and add the TXT record. Verification takes 15–30 minutes.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</div>
                        <div>
                            <p class="font-semibold text-slate-800">Add MX records</p>
                            <p class="text-slate-500">Replace existing MX records with Google's:</p>
                            <div class="mt-2 bg-slate-800 text-slate-200 rounded-lg p-3 font-mono text-xs space-y-1">
                                <div>ASPMX.L.GOOGLE.COM      priority 1</div>
                                <div>ALT1.ASPMX.L.GOOGLE.COM priority 5</div>
                                <div>ALT2.ASPMX.L.GOOGLE.COM priority 5</div>
                                <div>ALT3.ASPMX.L.GOOGLE.COM priority 10</div>
                                <div>ALT4.ASPMX.L.GOOGLE.COM priority 10</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">4</div>
                        <div>
                            <p class="font-semibold text-slate-800">Create user accounts</p>
                            <p class="text-slate-500">In Google Admin console → Users, create accounts like <code>alice.beaumont@3gites.org</code>. Members then access email via <a href="https://mail.google.com" class="text-gold-600 underline" target="_blank">mail.google.com</a> with their new address.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">5</div>
                        <div>
                            <p class="font-semibold text-slate-800">Update Laravel MAIL_ settings</p>
                            <p class="text-slate-500">Use the admin Google Workspace account for outgoing Laravel mail. In your <code>.env</code>:</p>
                            <div class="mt-2 bg-slate-800 text-slate-200 rounded-lg p-3 font-mono text-xs space-y-1">
                                <div>MAIL_MAILER=smtp</div>
                                <div>MAIL_HOST=smtp.gmail.com</div>
                                <div>MAIL_PORT=587</div>
                                <div>MAIL_USERNAME=admin@3gites.org</div>
                                <div>MAIL_PASSWORD=&lt;app-password-from-google&gt;</div>
                                <div>MAIL_ENCRYPTION=tls</div>
                                <div>MAIL_FROM_ADDRESS=admin@3gites.org</div>
                            </div>
                            <p class="text-slate-400 mt-2 text-xs">Generate an App Password in Google Account → Security → 2-Step Verification → App passwords.</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            {{-- Option B: Zoho Mail --}}
            <div>
                <h2 class="font-playfair text-xl font-bold text-navy-900 mb-4">Option B — Zoho Mail (Free Tier Available)</h2>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</div>
                        <div>
                            <p class="font-semibold text-slate-800">Sign up at Zoho Mail</p>
                            <p class="text-slate-500">Go to <a href="https://www.zoho.com/mail" target="_blank" class="text-gold-600 underline">zoho.com/mail</a>. The free plan supports up to 5 users with 5GB storage each — enough for the admin team.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</div>
                        <div>
                            <p class="font-semibold text-slate-800">Add &amp; verify your domain</p>
                            <p class="text-slate-500">Zoho will provide a TXT record for domain verification and MX records to add in Hostinger's DNS Zone Editor.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</div>
                        <div>
                            <p class="font-semibold text-slate-800">Add MX records (Zoho)</p>
                            <div class="mt-2 bg-slate-800 text-slate-200 rounded-lg p-3 font-mono text-xs space-y-1">
                                <div>mx.zoho.com      priority 10</div>
                                <div>mx2.zoho.com     priority 20</div>
                                <div>mx3.zoho.com     priority 50</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">4</div>
                        <div>
                            <p class="font-semibold text-slate-800">Laravel SMTP settings for Zoho</p>
                            <div class="mt-2 bg-slate-800 text-slate-200 rounded-lg p-3 font-mono text-xs space-y-1">
                                <div>MAIL_MAILER=smtp</div>
                                <div>MAIL_HOST=smtp.zoho.com</div>
                                <div>MAIL_PORT=587</div>
                                <div>MAIL_USERNAME=admin@3gites.org</div>
                                <div>MAIL_PASSWORD=&lt;your-zoho-password&gt;</div>
                                <div>MAIL_ENCRYPTION=tls</div>
                                <div>MAIL_FROM_ADDRESS=admin@3gites.org</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4 text-emerald-800">
                <strong>Recommendation:</strong> Use <strong>Zoho Mail free tier</strong> for the administrator-only
                inbox (admin@3gites.org) and personal forwarding for members who want an @3gites.org
                vanity address. Most members are fine keeping their personal email and simply
                logging into the portal with it.
            </div>

        </div>
    </div>
</div>

@endsection
