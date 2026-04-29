<x-layouts.admin>
    <x-slot name="title">
        Bank Connectors
    </x-slot>

    <x-slot name="content">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 pt-6">
            <div class="card p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">ConnectIPS API (Nepal)</h2>
                    <p class="text-sm text-gray-600">Save API credentials here from the admin panel, then click Bank Link.</p>
                </div>

                <x-form method="PATCH" :route="['bank-connectors.connectips.settings']">
                    <x-form.group.text name="base_url" label="Base URL" value="{{ old('base_url', $connectIps['base_url']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="merchant_id" label="Merchant ID" value="{{ old('merchant_id', $connectIps['merchant_id']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="app_id" label="App ID" value="{{ old('app_id', $connectIps['app_id']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="app_name" label="App Name" value="{{ old('app_name', $connectIps['app_name']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="username" label="Username" value="{{ old('username', $connectIps['username']) }}" form-group-class="mb-3" />
                    <x-form.group.password name="password" label="Password" value="{{ old('password', $connectIps['password']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="private_key_path" label="Private Key Path (storage/app)" value="{{ old('private_key_path', $connectIps['private_key_path']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="certificate_path" label="Certificate Path (storage/app)" value="{{ old('certificate_path', $connectIps['certificate_path']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="default_currency" label="Currency" value="{{ old('default_currency', $connectIps['default_currency']) }}" form-group-class="mb-3" />

                    <x-button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded" override="class">Save ConnectIPS API</x-button>
                </x-form>

                <div class="border-t pt-4">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <x-link href="{{ route('bank-connectors.connectips.link') }}" class="px-4 py-2 bg-green text-white rounded" override="class">Bank Link</x-link>
                    </div>

                    <x-form method="POST" :route="['bank-connectors.connectips.validate']">
                        <x-form.group.text name="reference_id" label="Reference ID" value="{{ old('reference_id') }}" required="required" form-group-class="mb-3" />
                        <x-form.group.text name="amount" label="Amount" value="{{ old('amount', '1') }}" required="required" form-group-class="mb-3" />

                        <x-button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded" override="class">Validate Statement Link</x-button>
                    </x-form>
                </div>

                @if (session()->has('connectips_validation'))
                    <div class="mt-4 p-3 bg-gray-50 rounded text-xs overflow-auto">
                        <pre>{{ json_encode(session('connectips_validation'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>

            <div class="card p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Australian Open Banking API (Basiq)</h2>
                    <p class="text-sm text-gray-600">Configure Basiq in admin, link a bank in one click, then sync statements.</p>
                </div>

                <x-form method="PATCH" :route="['bank-connectors.basiq.settings']">
                    <x-form.group.text name="base_url" label="API Base URL" value="{{ old('base_url', $basiq['base_url']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="auth_url" label="OAuth Authorize URL" value="{{ old('auth_url', $basiq['auth_url']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="token_url" label="OAuth Token URL" value="{{ old('token_url', $basiq['token_url']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="client_id" label="Client ID" value="{{ old('client_id', $basiq['client_id']) }}" form-group-class="mb-3" />
                    <x-form.group.password name="client_secret" label="Client Secret" value="{{ old('client_secret', $basiq['client_secret']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="scope" label="Scope" value="{{ old('scope', $basiq['scope']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="redirect_uri" label="Redirect URI" value="{{ old('redirect_uri', $basiq['redirect_uri']) }}" form-group-class="mb-3" />
                    <x-form.group.text name="statements_path" label="Statements Path" value="{{ old('statements_path', $basiq['statements_path']) }}" form-group-class="mb-3" />

                    <x-button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded" override="class">Save Basiq API</x-button>
                </x-form>

                <div class="border-t pt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $isBasiqLinked ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $isBasiqLinked ? 'Linked' : 'Not linked' }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <x-link href="{{ route('bank-connectors.basiq.connect') }}" class="px-4 py-2 bg-blue-600 text-white rounded" override="class">Bank Link</x-link>
                        <x-link href="{{ route('bank-connectors.basiq.statements') }}" class="px-4 py-2 bg-gray-800 text-white rounded" override="class">Sync Statements</x-link>

                        @if ($isBasiqLinked)
                            <x-form method="DELETE" :route="['bank-connectors.basiq.disconnect']">
                                <x-button type="submit" class="px-4 py-2 bg-red-600 text-white rounded" override="class">Disconnect</x-button>
                            </x-form>
                        @endif
                    </div>
                </div>

                @if (session()->has('basiq_statements'))
                    <div class="mt-4 p-3 bg-gray-50 rounded text-xs overflow-auto max-h-96">
                        <pre>{{ json_encode(session('basiq_statements'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
