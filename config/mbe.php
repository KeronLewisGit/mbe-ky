<?php

/*
|--------------------------------------------------------------------------
| Mail Boxes Etc. Cayman Islands — site content & rates
|--------------------------------------------------------------------------
| Everything a store manager is likely to change (contact details, hours,
| prices, shipping rates, external portals) lives here so the views and
| the calculators stay in sync.
*/

return [

    'name' => 'Mail Boxes Etc.',
    'region' => 'Cayman Islands',
    'tagline' => '#PeoplePossible',

    'phone' => '(345) 745-1400',
    'phone_href' => '+13457451400',

    'emails' => [
        'general' => 'info@mbe.ky',
        'ebox' => 'cby@mbe.ky',
        'ocean' => 'oceanship@mbe.ky',
        'print' => 'print@mbe.ky',
    ],

    'corporate_address' => ['10 Market Street, Camana Bay', 'KY1-9006, Cayman Islands'],

    'locations' => [
        'camana-bay' => [
            'name' => 'Camana Bay',
            'label' => 'Flagship store',
            'address' => '10 Market Street, Camana Bay',
            'perk' => '24-hour indoor mailbox access',
            'hours' => [['Mon – Fri', '9am – 6pm'], ['Saturday', '9am – 5pm'], ['Sunday', 'Closed']],
            'weekly' => [0 => null, 1 => [9, 18], 2 => [9, 18], 3 => [9, 18], 4 => [9, 18], 5 => [9, 18], 6 => [9, 17]],
            'directions' => 'https://www.google.com/maps/search/?api=1&query=Mail+Boxes+Etc+10+Market+Street+Camana+Bay+Grand+Cayman',
            'virtual_signup' => 'https://mailboxesetccaymanislands.anytimemailbox.com/signup',
        ],
        'harbour-walk' => [
            'name' => 'Harbour Walk',
            'label' => 'Grand Harbour · Red Bay',
            'address' => '52 Edgewater Way, Harbour Walk, Red Bay',
            'perk' => 'All the services of our flagship store',
            'hours' => [['Tue – Fri', '10am – 6pm'], ['Saturday', '9am – 3pm'], ['Sun & Mon', 'Closed']],
            'hours_note' => 'Closed daily 12:00 – 12:30pm',
            'weekly' => [0 => null, 1 => null, 2 => [10, 18], 3 => [10, 18], 4 => [10, 18], 5 => [10, 18], 6 => [9, 15]],
            'directions' => 'https://www.google.com/maps/search/?api=1&query=Mail+Boxes+Etc+Harbour+Walk+Grand+Cayman',
            'virtual_signup' => 'https://mailboxesetccaymanharbourwalk.anytimemailbox.com/signup',
        ],
    ],

    'timezone' => 'America/Cayman',

    'links' => [
        'ebox_login' => 'https://mbe-latam.com/eboxweb/',
        'ebox_signup' => 'https://mbe-latam.com/registro/cby/ebox',
        'virtual_login' => 'https://mailboxesetccaymanislands.anytimemailbox.com/',
        'cols_register' => 'https://online.gov.ky/cols/faces/userregistration',
        'cols_login' => 'https://online.gov.ky/cols/faces/pages/login.jsf',
        'mbe_global' => 'https://www.mbeglobal.com',
        'facebook' => 'https://www.facebook.com/MBECayman/',
    ],

    // E-box air freight (CI$). Source: mbe.ky/e-box pricing tab.
    'ebox' => [
        'plans' => [
            'lite' => [
                'name' => 'e-box Lite',
                'membership' => 0,
                'first_lb' => 12.95,
                'per_lb' => 4.95,
                'blurb' => 'Best for first time buyers and occasional users.',
            ],
            'pro' => [
                'name' => 'e-box Pro',
                'membership' => 49,
                'first_lb' => 9.95,
                'per_lb' => 3.95,
                'blurb' => 'Best value plan. Low shipping rates and one low annual membership fee.',
            ],
        ],
        'document_per_oz' => 0.60,
        'dim_divisor' => 166,
        'transit' => '5–7 business days',
        'address' => ['First & Last Name', '2250 NW 114th Ave', 'Unit 1Y CBY XXXX', 'Doral, FL 33192-4177'],
        'po_box' => ['First & Last Name CBY XXXX', 'PO BOX 029011', 'Miami, FL 33102-9011'],
        'points' => [
            ['Ship a package', 7],
            ['Pre-alert a package', 5],
            ['Register on e-box web (one time)', 100],
            ['On your birthday (once a year)', 500],
        ],
    ],

    // Ocean freight (CI$). Source: mbe.ky/ocean-ship.
    'ocean' => [
        'flat_cf' => 12,
        'flat_rate' => 159,
        'mid_cf' => 70,
        'mid_rate' => 8,
        'high_rate' => 6,
        'divisor' => 1728,
        'storage_per_day' => 3.50,
        'address' => ['Your name CBY#', 'c/o Mail Boxes Etc./GCM', '8001 NW 79th Ave.', 'Miami, FL, 33166'],
    ],

    // Mailbox rental (CI$). Source: mbe.ky/physical and mbe.ky/mailboxes.
    'mailbox' => [
        'plans' => [
            'personal' => ['name' => 'Personal', 'price' => 185, 'recipients' => 2, 'for' => 'Individuals and couples'],
            'small-business' => ['name' => 'Small Business', 'price' => 275, 'recipients' => 4, 'for' => 'Home offices and start-ups'],
            'corporate' => ['name' => 'Corporate', 'price' => 389, 'recipients' => 4, 'for' => 'High-volume mail and parcels'],
        ],
        'key_deposit' => 25,
        'extra_recipient' => 75,
        'virtual_month' => 19.99,
        'virtual_year' => 200,
    ],

    'couriers' => ['UPS', 'FedEx', 'DHL', 'EMS', 'Postal services'],

    // Source: MBE Cayman Facebook, 12 May 2026.
    'print_from' => 0.75,

    'print_products' => [
        'Business Cards', 'Letterheads', 'Brochures', 'Leaflets & Flyers', 'Postcards', 'Booklets',
        'Labels', 'Invitations', 'Calendars', 'Compliment Slips', 'Holiday Photo Cards',
    ],

    'legal' => [
        'user-guide' => ['title' => 'E-box User Guide', 'group' => 'E-box', 'summary' => 'A step-by-step guide to your first order and what to know before using e-box.'],
        'dangerous-and-prohibited-goods' => ['title' => 'Dangerous & Prohibited Goods', 'group' => 'E-box', 'summary' => 'Items deemed hazardous by transport authorities.'],
        'terms-and-conditions' => ['title' => 'E-box Terms & Conditions', 'group' => 'E-box', 'summary' => 'The e-box Terms and Conditions of Service Agreement.'],
        'physical-mailbox-terms' => ['title' => 'Physical Mailbox Terms & Conditions', 'group' => 'Mailbox service', 'summary' => 'Private Mail Suite (physical mailbox) terms and conditions.'],
        'virtual-mailbox-terms' => ['title' => 'Virtual Mailbox Terms & Conditions', 'group' => 'Mailbox service', 'summary' => 'Terms & Conditions for Virtual Mailbox renters.'],
        'virtual-mailbox-privacy' => ['title' => 'Virtual Mailbox Privacy Policy', 'group' => 'Mailbox service', 'summary' => 'How Anytime Mailbox handles your personal information.'],
    ],

];
