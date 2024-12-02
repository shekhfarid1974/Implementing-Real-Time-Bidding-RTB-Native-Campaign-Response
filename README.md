Real-Time Bidding (RTB) Native Campaign Handler

Overview

This PHP project handles bid requests and generates suitable responses for native campaigns in RTB scenarios.

Files

1. `bid_request_handler.php` - Main script to process bid requests.
2. `campaigns.php` - Contains predefined campaign details.
3. `helpers.php` - Utility functions for validation, selection, and response generation.
4. `README.md` - Documentation file.

How to Run

1. Clone the repository:  
   `git clone <repository_url>`
2. Start a PHP server:  
   `php -S localhost:8000`
3. Send a POST request to the server with a bid request JSON.  
   Example:  {
    "id": "12143451",
    "imp": [
        {
            "id": "1",
            "bidfloor": 0.10,
            "native": {
                "assets": [
                    {
                        "id": 1,
                        "required": 1,
                        "title": {
                            "len": 25
                        }
                    },
                    {
                        "id": 2,
                        "required": 1,
                        "data": {
                            "type": 2
                        }
                    }
                ]
            }
        }
    ],
    "device": {
        "ua": "Mozilla/5.0",
        "geo": {
            "country": "Bangladesh"
        },
        "make": "TECNO"
    },
    "user": {
        "id": "123"
    }
}



And Return Response 200

{
    "id": "12143451",
    "bidid": "674ddbd82bb9a",
    "seatbid": [
        {
            "bid": [
                {
                    "price": 0.15,
                    "adm": "{\"native\":{\"assets\":[{\"id\":101,\"title\":{\"text\":\"Transsion_Native_Campaign_Test_Nov_30_2024\"},\"required\":1},{\"id\":102,\"data\":{\"value\":\"TestGP\"},\"required\":1}],\"link\":{\"url\":\"https:\\/\\/gamestar.shabox.mobi\\/\"}}}",
                    "impid": "1",
                    "crid": "168962",
                    "bundle": "CPM"
                }
            ],
            "seat": "1",
            "group": 0
        }
    ]
}