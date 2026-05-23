<?php

namespace App\Services\BankConnectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ConnectIpsService
{
    protected function cfg(string $key, $default = null)
    {
        return setting('bank_connectors.connectips.' . $key, config('services.connectips.' . $key, $default));
    }

    public function buildWebPayload(string $referenceId, string $amount, ?string $remarks = null, ?string $particulars = null): array
    {
        $txnId = 'TXN-' . now()->format('YmdHis') . '-' . strtoupper(substr(md5((string) microtime(true)), 0, 6));
        $txnDate = now()->format('d-m-Y');

        $data = [
            'MERCHANTID' => (string) $this->cfg('merchant_id'),
            'APPID' => (string) $this->cfg('app_id'),
            'APPNAME' => (string) $this->cfg('app_name', 'Akaunting'),
            'TXNID' => $txnId,
            'TXNDATE' => $txnDate,
            'TXNCRNCY' => (string) $this->cfg('default_currency', 'NPR'),
            'TXNAMT' => (string) $amount,
            'REFERENCEID' => $referenceId,
            'REMARKS' => $remarks ?: 'Bank Link via Akaunting',
            'PARTICULARS' => $particulars ?: 'Bank Link',
        ];

        $message = sprintf(
            'MERCHANTID=%s,APPID=%s,APPNAME=%s,TXNID=%s,TXNDATE=%s,TXNCRNCY=%s,TXNAMT=%s,REFERENCEID=%s,REMARKS=%s,PARTICULARS=%s,TOKEN=TOKEN',
            $data['MERCHANTID'],
            $data['APPID'],
            $data['APPNAME'],
            $data['TXNID'],
            $data['TXNDATE'],
            $data['TXNCRNCY'],
            $data['TXNAMT'],
            $data['REFERENCEID'],
            $data['REMARKS'],
            $data['PARTICULARS']
        );

        $data['TOKEN'] = $this->signString($message);

        return $data;
    }

    public function getGatewayUrl(): string
    {
        return rtrim((string) $this->cfg('base_url'), '/') . '/connectipswebgw/loginpage';
    }

    public function validateTransaction(string $referenceId, string $amount): array
    {
        $merchantId = (string) $this->cfg('merchant_id');
        $appId = (string) $this->cfg('app_id');

        $tokenString = sprintf(
            'MERCHANTID=%s,APPID=%s,REFERENCEID=%s,TXNAMT=%s',
            $merchantId,
            $appId,
            $referenceId,
            $amount
        );

        $token = $this->signString($tokenString);

        $endpoint = rtrim((string) $this->cfg('base_url'), '/') . '/connectipswebws/api/creditor/validatetxn';

        $response = Http::withBasicAuth(
            (string) $this->cfg('username', $appId),
            (string) $this->cfg('password')
        )->acceptJson()->post($endpoint, [
            'merchantId' => $merchantId,
            'appId' => $appId,
            'referenceId' => $referenceId,
            'txnAmt' => $amount,
            'token' => $token,
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'status' => 'HTTP_' . $response->status(),
                'raw' => $response->body(),
            ];
        }

        $json = $response->json();

        return [
            'success' => strtoupper((string) ($json['status'] ?? '')) === 'SUCCESS',
            'status' => (string) ($json['status'] ?? ''),
            'description' => (string) ($json['statusDesc'] ?? ''),
            'data' => $json,
        ];
    }

    public function signString(string $message): string
    {
        $privateKeyPath = (string) $this->cfg('private_key_path');

        if (! Storage::disk('local')->exists($privateKeyPath)) {
            throw new RuntimeException('ConnectIPS private key file not found.');
        }

        $privateKey = Storage::disk('local')->get($privateKeyPath);
        $resource = openssl_pkey_get_private($privateKey);

        if (! $resource) {
            throw new RuntimeException('Unable to load ConnectIPS private key.');
        }

        $signature = '';
        $ok = openssl_sign($message, $signature, $resource, OPENSSL_ALGO_SHA256);
        openssl_pkey_free($resource);

        if (! $ok) {
            throw new RuntimeException('ConnectIPS token signing failed.');
        }

        return base64_encode($signature);
    }
}
