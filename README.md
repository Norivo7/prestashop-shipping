# prestashop-shipping

## Overview

This repository contains a small recruitment project built on top of PrestaShop 8.

It covers two tasks:

1. **Header layout customization** based on the provided mockup.
2. **A custom PrestaShop module (`ps_shipping`)** that displays the lowest available shipping cost on the product page.

---

## What is included

### 1. Header customization

The default Classic theme header was adjusted to better match the provided design direction:

- top utility bar
- custom main header layout
- centered search area
- separated action boxes on the right side
- simplified, structured storefront header styling

The goal was not pixel-perfect reproduction, but rather a clean layout close to the supplied mockup.

### 2. `ps_shipping` module

A custom module was created under:

```text
modules/ps_shipping
```

The module is displayed on the product page and calculates the **lowest available shipping cost** among valid carriers.

---

## Requirements

To run the project locally, you need:

- Docker
- Docker Compose

---

## Running the project

### 1. Start the containers

From the project root, run:

```bash
docker compose up -d
```

This starts:

- PrestaShop 8
- MySQL 5.7
- phpMyAdmin

### 2. Open the storefront

```text
http://localhost:8080
```

### 3. Open phpMyAdmin

```text
http://localhost:8081
```

---

## Back office access

After the first installation, PrestaShop renaming the admin folder.

### 1. Find the generated admin directory

Run:

```bash
docker compose exec prestashop bash
```

Rename the admin folder, for example:
```text
mv admin admin-presta
```

### 2. Open the back office

Use:

```text
http://localhost:8080/admin-presta
```

### 3. Default login credentials

Credentials:
```
Login: demo@prestashop.com
Password: prestashop_demo
```

---

## Installing and testing the `ps_shipping` module

### 1. Go to the back office

Open the generated admin URL.

### 2. Open the Module Manager

Navigate to:

```text
Modules > Module Manager
```

### 3. Find the module

Search for:

```text
ps_shipping
```

or:

```text
Shipping
```

### 4. Install the module

Click **Install**.

### 5. Create or open a product

Navigate to:

```text
Catalog > Products
```

Create a simple product or open an existing one.

### 6. Open the product page on the storefront

The module renders an additional line on the product page:

```text
Lowest delivery cost: ...
```

---

## How the shipping module works

The module:

1. reads the current product ID from the product page hook,
2. loads the product,
3. retrieves available carriers,
4. filters out unsupported or unwanted carriers,
5. estimates delivery cost depending on carrier shipping method,
6. displays the lowest valid result.

### Current implementation 


- shipping is calculated for a **single product**,
- the **default shop zone** is used,
- **pickup-type carriers** can be ignored,
- only valid and active carriers are taken into account.

### Current limitations

The current implementation does **not** fully simulate all possible PrestaShop shipping scenarios.

It does not yet cover:

- full cart-based shipping calculation,
- customer-specific address context,
- all external carrier-module rules,
- caching,
- advanced country / customer-group / checkout context.

For a production-ready version, the next step would be to move toward a cart-based calculation strategy using PrestaShop shipping APIs closer to the real checkout flow.

---

## Header customization notes

The header was customized by overriding:

```text
themes/classic/templates/_partials/header.tpl
```

and by loading custom CSS through:

```text
themes/classic/assets/css/custom-header.css
```

The current version focuses on:

- structure,
- spacing,
- layout grouping,
- visual separation of the main header zones.

It is intentionally not a pixel-perfect clone of the mockup, but rather a close functional approximation.

---

## Troubleshooting

### PrestaShop does not open immediately after startup

On a fresh install, the container may need a short moment to complete installation.

Check logs with:

```bash
docker compose logs -f prestashop
```

### Clear cache after changing theme overrides

Run:

```bash
docker compose exec prestashop rm -rf /var/www/html/var/cache/*
```

### If the admin folder is unknown

Run:

```bash
docker compose exec prestashop ls -la /var/www/html | grep admin
```

---

## Suggested review flow

A reviewer can verify the task by following this path:

1. start the project with Docker,
2. open the storefront,
3. review the customized header,
4. log in to the back office,
5. install the `ps_shipping` module,
6. open a product page,
7. verify that the lowest shipping cost is displayed.

