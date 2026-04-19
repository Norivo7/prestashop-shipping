{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *}

{block name='header_banner'}
  <div class="header-banner">
    {hook h='displayBanner'}
  </div>
{/block}

<link rel="stylesheet" href="{$urls.theme_assets}css/custom-header.css">

{block name='header_nav'}
  <nav class="header-nav site-topbar">
    <div class="container">
      <div class="site-topbar__inner hidden-sm-down">
        <div class="site-topbar__left">
          <a href="#">Centrum pomocy</a>
          <a href="#">Strefa wiedzy</a>
          <a href="#">Kontakt</a>
        </div>

        <div class="site-topbar__right">
          <span>Lorem ipsum</span>
        </div>
      </div>

      <div class="hidden-md-up text-sm-center mobile">
        <div class="float-xs-left" id="menu-icon">
          <i class="material-icons d-inline">&#xE5D2;</i>
        </div>
        <div class="float-xs-right" id="_mobile_cart"></div>
        <div class="float-xs-right" id="_mobile_user_info"></div>
        <div class="top-logo" id="_mobile_logo"></div>
        <div class="clearfix"></div>
      </div>
    </div>
  </nav>
{/block}

{block name='header_top'}
  <div class="site-header hidden-sm-down">
    <div class="container">
      <div class="site-header__inner">

        <div class="site-header__logo" id="_desktop_logo">
          {if $shop.logo_details}
            {if $page.page_name == 'index'}
              <h1 class="site-header__logo-title">
                {renderLogo}
              </h1>
            {else}
              {renderLogo}
            {/if}
          {/if}
        </div>

        <div class="site-header__search">
          {hook h='displaySearch'}
        </div>

        <div class="site-header__actions">
          <div class="site-header-box">
            <span>Ulubione</span>
          </div>
          <div class="site-header-box">
            <span>Konto</span>
          </div>
          <div class="site-header-box">
            <span>Koszyk</span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="site-menu hidden-sm-down">
    <div class="container">
      {hook h='displayNavFullWidth'}
    </div>
  </div>

  <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:none;">
    <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
    <div class="js-top-menu-bottom">
      <div id="_mobile_currency_selector"></div>
      <div id="_mobile_language_selector"></div>
      <div id="_mobile_contact_link"></div>
    </div>
  </div>
{/block}