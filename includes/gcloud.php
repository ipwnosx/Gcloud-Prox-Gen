<?php
/******************************************************************************
 *
 * Create virtual servers in cloud with Squid 3 proxy ready to go
 * Edit config.php to set proxy username and password.
 *
 ******************************************************************************/

define("GCLOUD_BIN", "/usr/bin/gcloud");

if (!file_exists(GCLOUD_BIN)) {
    echo "Please install Google Cloud SDK\n";
    echo "\n";
    echo "https://www.serverok.in/install-google-cloud-sdk";
    echo "\n\n";
    exit;
}

$dcRegions = [
    [
        'region' => 'us-east1',
        'location' => 'Moncks Corner, South Carolina, USA',
        'zones' => ['us-east1-b', 'us-east1-c', 'us-east1-d']
    ],
    [
        'region' => 'us-east4',
        'location' => 'Ashburn, Virginia, USA',
        'zones' => ['us-east4-a', 'us-east4-b', 'us-east4-c' ]
    ],
    [
        'region' => 'us-west1',
        'location' => 'The Dalles, Oregon, USA',
        'zones' => ['us-west1-a', 'us-west1-b', 'us-west1-c']
    ],
    [
        'region' => 'us-west2',
        'location' => 'Los Angeles, California, USA',
        'zones' => ['us-west2-a', 'us-west2-b', 'us-west2-c']
    ],
    [
        'region' => 'us-central1',
        'location' => 'Council Bluffs, Iowa, USA',
        'zones' => ['us-central1-a', 'us-central1-b', 'us-central1-c', 'us-central1-f']
    ],
    [
        'region' => 'southamerica-east1',
        'location' => 'São Paulo, Brazil',
        'zones' => ['southamerica-east1-a', 'southamerica-east1-b', 'southamerica-east1-c']
    ],
    [
        'region' => 'northamerica-northeast1',
        'location' => 'Montréal, Canada',
        'zones' => ['northamerica-northeast1-a', 'northamerica-northeast1-b', 'northamerica-northeast1-c']
    ],
    [
        'region' => 'europe-west1',
        'location' => 'St. Ghislain, Belgium',
        'zones' => ['europe-west1-b', 'europe-west1-c', 'europe-west1-d']
    ],
    [
        'region' => 'europe-west2',
        'location' => 'London, U.K',
        'zones' => ['europe-west2-a', 'europe-west2-b', 'europe-west2-c']
    ],
    [
        'region' => 'europe-west3',
        'location' => 'Frankfurt, Germany',
        'zones' => ['europe-west3-a', 'europe-west3-b', 'europe-west3-c']
    ],
    [
        'region' => 'europe-west4',
        'location' => 'Eemshaven, Netherlands',
        'zones' => ['europe-west4-a', 'europe-west4-b', 'europe-west4-c']
    ],
    [
        'region' => 'europe-north1',
        'location' => 'Hamina, Finland',
        'zones' => ['europe-north1-a', 'europe-north1-b', 'europe-north1-c']
    ],
    [
        'region' => 'asia-east1',
        'location' => 'Changhua County, Taiwan',
        'zones' => ['asia-east1-a', 'asia-east1-b', 'asia-east1-c']
    ],
    [
        'region' => 'asia-northeast1',
        'location' => 'Tokyo, Japan',
        'zones' => ['asia-northeast1-a', 'asia-northeast1-b', 'asia-northeast1-c']
    ],
    [
        'region' => 'asia-south1',
        'location' => 'Mumbai, India',
        'zones' => ['asia-south1-a', 'asia-south1-b', 'asia-south1-c']
    ],
    [
        'region' => 'asia-southeast1',
        'location' => 'Jurong West, Singapore',
        'zones' => ['asia-southeast1-a', 'asia-southeast1-b', 'asia-southeast1-c']
    ],
    [
        'region' => 'asia-east2',
        'location' => 'Hong Kong',
        'zones' => ['asia-east2-a', 'asia-east2-b', 'asia-east2-c']
    ],
    [
        'region' => 'australia-southeast1',
        'location' => 'Sydney, Australia',
        'zones' => ['australia-southeast1-a', 'australia-southeast1-b', 'australia-southeast1-c']
    ]
];

$gcloudInstnaceTypes = [
    [
        'type' => 'f1-micro',
        'info' => 'micro (1 shared vCPU) 0.6 GB memory, f1-micro'
    ],
    [
        'type' => 'g1-small',
        'info' => 'small (1 shared vCPU) 1.7 GB memory, g1-small'
    ],
    [
        'type' => 'n1-standard-1',
        'info' => '1 vCPU 3.75 GB memory, n1-standard-1'
    ],
    [
        'type' => 'n1-standard-2',
        'info' => '2 vCPU 7.5 GB memory, n1-standard-2'
    ],
    [
        'type' => 'n1-standard-4',  
        'info' => '4 vCPU 15 GB memory, n1-standard-4'
    ]
];
