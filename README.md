# Wards ACE Monero Gateway for WordPress

A Monero (XMR) payment gateway plugin for WooCommerce. Accept XMR payments on your WordPress store with integrated address support, QR codes, "Open in Wallet" functionality, live XMR pricing, and checkout discounts.

**Forked from [monero-integrations/monerowp](https://github.com/monero-integrations/monerowp)** with significant enhancements.

## What's New in This Fork

- **"Open in Wallet" button** — One-click `monero:` URI link on the payment page. Works with Monero GUI, Cake Wallet (mobile), Monerujo, and any wallet that registers the `monero:` protocol handler.
- **Checkout discount banner** — Dynamically shows how much customers save when selecting Monero at checkout (e.g., "You save $15.00 (3% off)!"). Works with both classic and block-based checkout.
- **Admin payments list hardened** — Gracefully handles deleted orders instead of fatal errors.
- **WordPress 6.9+ / WooCommerce 10.x compatible** — Tested with the latest versions.
- **PHP 8.3 compatible** — No deprecation warnings.

## Features

- Accept Monero (XMR) payments directly — no third-party payment processor
- Two verification modes: **monero-wallet-rpc** or **viewkey** (explorer-based)
- Automatic exchange rate conversion (USD, EUR, GBP, BTC, and more)
- Integrated addresses for each order (no payment ID confusion)
- QR code generation on the payment page
- Configurable payment expiration time
- Order confirmation after N block confirmations
- Optional: display all store prices in XMR
- Optional: percentage discount for Monero payments
- Cron-based payment monitoring (checks every minute)
- Shortcodes: `[monero-price]` and `[monero-accepted-here]`

## Requirements

- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4+ (8.x recommended)
- One of:
  - **monero-wallet-rpc** running on your server (recommended)
  - OR a Monero **secret viewkey** for explorer-based verification

## Installation

1. Download the latest release or clone this repo into `wp-content/plugins/`
2. Activate the plugin in WordPress admin → Plugins
3. Go to WooCommerce → Settings → Payments → Monero Gateway
4. Configure your Monero address and choose a verification method:
   - **wallet-rpc**: Enter your daemon host/port (default: `127.0.0.1:18080`)
   - **viewkey**: Enter your secret viewkey

### Running monero-wallet-rpc

```bash
# Start monerod (full node)
monerod --detach

# Start wallet-rpc (replace with your wallet file)
monero-wallet-rpc --wallet-file /path/to/wallet \
  --password "your-wallet-password" \
  --rpc-bind-port 18082 \
  --disable-rpc-login \
  --trusted-daemon
```

Systemd unit files are included in `assets/systemd-unit-files/` for Linux servers.

## Configuration

| Setting | Description | Default |
|---------|-------------|---------|
| **Monero Address** | Your primary Monero address (starts with `4`) | — |
| **Verification** | `monero-wallet-rpc` or `viewkey` | `monero-wallet-rpc` |
| **Secret Viewkey** | Only needed for viewkey mode | — |
| **Daemon Host** | monero-wallet-rpc host | `127.0.0.1` |
| **Daemon Port** | monero-wallet-rpc port | `18080` |
| **Discount (%)** | Percentage discount for Monero payments | `0` |
| **Order Valid Time** | Seconds before payment expires | `3600` (1 hour) |
| **Confirmations** | Block confirmations required | `1` |
| **Show QR Code** | Display QR code on payment page | Yes |
| **Testnet** | Use testnet instead of mainnet | No |

## Shortcodes

- `[monero-price]` — Shows the current XMR exchange rate (e.g., "1 XMR = 187.50 USD")
- `[monero-price currency="BTC"]` — Show rate in a specific currency
- `[monero-accepted-here]` — Displays the "Monero Accepted Here" badge

## Credits

- Original plugin: [monero-integrations/monerowp](https://github.com/monero-integrations/monerowp) by Monero Integrations & Ryo Currency Project
- Cryptographic libraries: Ed25519, SHA3, Base58 implementations included

## License

MIT License — see [LICENSE](LICENSE) for details.
