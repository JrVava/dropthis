<?php
return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    // 'active_url' => '/',

    'menu' => [
      [
        'text' => 'Menu Navigation',
        'is_header' => true
      ],
      [
        'route' => 'home',
        'icon' => 'bi bi-cpu',
        'text' => 'Dashboard'
      ],[
        'icon' => 'fas fa-link',
        'text' => 'Manage Links',
        'highlight' => true,
        'children' => [[
            'route' => 'groups',
            'text' => 'Groups'
          ],[
            'route' => 'links',
            'text' => 'Links'
          ],[
            'route' => 'settings',
            'text' => 'Setup'
          ]
        ]
      ],[
        'icon' => 'fas fa-bullhorn',
        'text' => 'Manage Promos',
        'highlight' => true,
        'children' => [[
            'route' => 'campaigns',
            'text' => 'Sendouts'
          ],[
            'route' => 'emails',
            'text' => 'E-Mails'
          ],
        ]
      ],[
        'icon'=>'fas fa-rocket',
        'text' =>'Manage Releases',
        'highlight' => true,
          'children' => [[
              'text' => 'Smartlinks',
              'route' => 'releases',
          ],[
            'text' => 'Stores',
            'route' => 'stores',
          ],
          // [
          //   'text' => 'Themes'
          // ]
        ]
      ],[
        'icon'=>'fas fa-cogs',
        'text' =>'Admin Settings',
        'highlight' => true,
          'children' => [
            [
              'text' => 'Coupons',
              'route' => 'coupons'
            ],[
              'text' => 'Domains',
              'route' => 'domains'
          ],[
            'text' => 'Plans',
            'route' => 'plans'
          ],[
            'text' => 'Users',
            'route' => 'users'
          ]
        ]
      ],
      
      [
        'route' => 'billing',
        'icon' => 'far fa-money-bill-alt',
        'text' => 'Billing'
      ],
            // [
      //   'route' => 'groups',
      //   'icon' => 'bi bi-columns-gap',
      //   'text' => 'Groups'
      // ],
      // [
      //   'route' => 'links',
      //   'icon' => 'bi bi-link',
      //   'text' => 'Links'
      // ],
      // [
      //   'route' => 'domains',
      //   'icon' => 'bi bi-cloud-fill',
      //   'text' => 'Domains'
      // ],
      // [
      //   'route' => 'settings',
      //   'icon' => 'bi bi-gear',
      //   'text' => 'Link Settings'
      // ],
      // [
      //   'route' => 'campaigns',
      //   'icon' => 'fas fa-bullhorn',
      //   'text' => 'Promo Campaigns'
      // ],
      // [
      //   'route' => 'emails',
      //   'icon' => 'bi bi-envelope',
      //   'text' => 'E-Mails'
      // ],
      // [
      //   'route' => 'plans',
      //   'icon' => 'fas fa-th-large',
      //   'text' => 'Plans'
      // ],
      // [
      //   'route' => 'coupons',
      //   'icon' => 'fas fa-dollar-sign',
      //   'text' => 'Coupons'
      // ],[
      //   'route' => 'users',
      //   'icon' => 'fas fa-users',
      //   'text' => 'Users'
      // ],
    ]
];
