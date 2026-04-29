<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Services\BankConnectors\BasiqService;
use App\Services\BankConnectors\ConnectIpsService;
use Illuminate\Http\Request;
use Throwable;

class BankConnectors extends Controller
{
    public function index()
    {
        $connectIps = [
            'base_url' => (string) setting('bank_connectors.connectips.base_url', config('services.connectips.base_url')),
            'merchant_id' => (string) setting('bank_connectors.connectips.merchant_id', config('services.connectips.merchant_id')),
            'app_id' => (string) setting('bank_connectors.connectips.app_id', config('services.connectips.app_id')),
            'app_name' => (string) setting('bank_connectors.connectips.app_name', config('services.connectips.app_name', 'Akaunting')),
            'username' => (string) setting('bank_connectors.connectips.username', config('services.connectips.username')),
            'password' => (string) setting('bank_connectors.connectips.password', config('services.connectips.password')),
            'private_key_path' => (string) setting('bank_connectors.connectips.private_key_path', config('services.connectips.private_key_path', 'connectips/private_key.pem')),
            'certificate_path' => (string) setting('bank_connectors.connectips.certificate_path', config('services.connectips.certificate_path', 'connectips/certificate.pem')),
            'default_currency' => (string) setting('bank_connectors.connectips.default_currency', config('services.connectips.default_currency', 'NPR')),
        ];

        $basiq = [
            'base_url' => (string) setting('bank_connectors.basiq.base_url', config('services.basiq.base_url')),
            'auth_url' => (string) setting('bank_connectors.basiq.auth_url', config('services.basiq.auth_url')),
            'token_url' => (string) setting('bank_connectors.basiq.token_url', config('services.basiq.token_url')),
            'client_id' => (string) setting('bank_connectors.basiq.client_id', config('services.basiq.client_id')),
            'client_secret' => (string) setting('bank_connectors.basiq.client_secret', config('services.basiq.client_secret')),
            'scope' => (string) setting('bank_connectors.basiq.scope', config('services.basiq.scope', 'openid')),
            'redirect_uri' => (string) setting('bank_connectors.basiq.redirect_uri', config('services.basiq.redirect_uri')),
            'statements_path' => (string) setting('bank_connectors.basiq.statements_path', config('services.basiq.statements_path', '/users/me/transactions')),
        ];

        $basiqToken = json_decode((string) setting('bank_connectors.basiq.token', '{}'), true);
        $isBasiqLinked = ! empty($basiqToken['access_token']);

        return view('banking.connectors.index', compact('connectIps', 'basiq', 'isBasiqLinked'));
    }

    public function saveConnectIpsSettings(Request $request)
    {
        $data = $request->validate([
            'base_url' => 'required|url|max:255',
            'merchant_id' => 'required|string|max:255',
            'app_id' => 'required|string|max:255',
            'app_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'private_key_path' => 'required|string|max:255',
            'certificate_path' => 'required|string|max:255',
            'default_currency' => 'required|string|max:10',
        ]);

        setting()->set('bank_connectors.connectips.base_url', $data['base_url']);
        setting()->set('bank_connectors.connectips.merchant_id', $data['merchant_id']);
        setting()->set('bank_connectors.connectips.app_id', $data['app_id']);
        setting()->set('bank_connectors.connectips.app_name', $data['app_name']);
        setting()->set('bank_connectors.connectips.username', $data['username']);
        setting()->set('bank_connectors.connectips.password', $data['password']);
        setting()->set('bank_connectors.connectips.private_key_path', $data['private_key_path']);
        setting()->set('bank_connectors.connectips.certificate_path', $data['certificate_path']);
        setting()->set('bank_connectors.connectips.default_currency', $data['default_currency']);
        setting()->save();

        return redirect()->route('bank-connectors.index')->with('success', 'ConnectIPS API settings saved.');
    }

    public function saveBasiqSettings(Request $request)
    {
        $data = $request->validate([
            'base_url' => 'required|url|max:255',
            'auth_url' => 'required|url|max:255',
            'token_url' => 'required|url|max:255',
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
            'scope' => 'required|string|max:255',
            'redirect_uri' => 'required|url|max:255',
            'statements_path' => 'required|string|max:255',
        ]);

        setting()->set('bank_connectors.basiq.base_url', $data['base_url']);
        setting()->set('bank_connectors.basiq.auth_url', $data['auth_url']);
        setting()->set('bank_connectors.basiq.token_url', $data['token_url']);
        setting()->set('bank_connectors.basiq.client_id', $data['client_id']);
        setting()->set('bank_connectors.basiq.client_secret', $data['client_secret']);
        setting()->set('bank_connectors.basiq.scope', $data['scope']);
        setting()->set('bank_connectors.basiq.redirect_uri', $data['redirect_uri']);
        setting()->set('bank_connectors.basiq.statements_path', $data['statements_path']);
        setting()->save();

        return redirect()->route('bank-connectors.index')->with('success', 'Basiq API settings saved.');
    }

    public function disconnectBasiq()
    {
        setting()->forget('bank_connectors.basiq.token');
        setting()->save();

        return redirect()->route('bank-connectors.index')->with('success', 'Basiq connection removed.');
    }

    public function connectIpsLink(Request $request, ConnectIpsService $connectIps)
    {
        try {
            $amount = (string) $request->query('amount', '1');
            $referenceId = 'CIPS-' . now()->format('YmdHis') . '-' . auth()->id();

            $payload = $connectIps->buildWebPayload($referenceId, $amount, 'Account link request', 'Connect account');

            return view('banking.connectors.connectips-redirect', [
                'url' => $connectIps->getGatewayUrl(),
                'data' => $payload,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('bank-connectors.index')->with('error', 'ConnectIPS link failed: ' . $e->getMessage());
        }
    }

    public function connectIpsReturn(Request $request)
    {
        $referenceId = (string) $request->query('txn_id', '');

        if ($referenceId === '') {
            return redirect()->route('bank-connectors.index')->with('warning', 'ConnectIPS returned without transaction reference.');
        }

        return redirect()->route('bank-connectors.index')->with('success', 'ConnectIPS returned reference: ' . $referenceId . '. Validate it below to confirm success.');
    }

    public function connectIpsValidate(Request $request, ConnectIpsService $connectIps)
    {
        $validated = $request->validate([
            'reference_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $result = $connectIps->validateTransaction((string) $validated['reference_id'], (string) $validated['amount']);

            $message = $result['success']
                ? 'ConnectIPS validation success.'
                : 'ConnectIPS validation failed: ' . ($result['description'] ?? $result['status'] ?? 'Unknown');

            return redirect()->route('bank-connectors.index')->with($result['success'] ? 'success' : 'error', $message)
                ->with('connectips_validation', $result);
        } catch (Throwable $e) {
            return redirect()->route('bank-connectors.index')->with('error', 'ConnectIPS validation error: ' . $e->getMessage());
        }
    }

    public function basiqConnect(BasiqService $basiq)
    {
        $state = bin2hex(random_bytes(16));
        session(['basiq_oauth_state' => $state]);

        return redirect()->away($basiq->authorizationUrl($state));
    }

    public function basiqCallback(Request $request, BasiqService $basiq)
    {
        $expectedState = (string) session('basiq_oauth_state', '');
        $state = (string) $request->query('state', '');
        $code = (string) $request->query('code', '');

        if ($expectedState === '' || $state === '' || ! hash_equals($expectedState, $state)) {
            return redirect()->route('bank-connectors.index')->with('error', 'Invalid Basiq OAuth state.');
        }

        if ($code === '') {
            return redirect()->route('bank-connectors.index')->with('error', 'Basiq authorization code missing.');
        }

        $token = $basiq->exchangeCodeForToken($code);

        if (! $token['success']) {
            return redirect()->route('bank-connectors.index')->with('error', 'Basiq token exchange failed.');
        }

        setting()->set('bank_connectors.basiq.token', json_encode($token));
        setting()->save();

        return redirect()->route('bank-connectors.index')->with('success', 'Australian bank connector linked using Basiq.');
    }

    public function basiqStatements(BasiqService $basiq)
    {
        $token = json_decode((string) setting('bank_connectors.basiq.token', '{}'), true);

        if (empty($token['access_token'])) {
            return redirect()->route('bank-connectors.index')->with('warning', 'Link Basiq first to fetch statements.');
        }

        $result = $basiq->fetchStatements((string) $token['access_token']);

        if (! $result['success']) {
            return redirect()->route('bank-connectors.index')->with('error', 'Statement sync failed from Basiq.');
        }

        return redirect()->route('bank-connectors.index')->with('success', 'Statements synced from Basiq successfully.')
            ->with('basiq_statements', $result['data']);
    }
}
