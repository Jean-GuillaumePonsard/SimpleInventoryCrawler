## Simple Inventory Crawler

### Prerequisites

As this project is based on the Laravel framework, you need to install:
- Mysql
- PHP >= 7.1.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension

### Installation

Run this command to clone the repository:

```git clone https://github.com/Jean-GuillaumePonsard/SimpleInventoryCrawler.git```

Go to the project folder

```cd SimpleInventoryCrawler```

Next, install the required dependencies by running this command: 

```composer install```

### Configuration

To create the configuration file,
copy ```.env.example``` to ```.env```

Then modify the database connexion parameters for your configuration:

```
DB_HOST=127.0.0.1               // Put your mysql server IP
DB_PORT=3306                    // Change the mysql port if necessary
DB_DATABASE=database_name       // Set the name you want the database to use
DB_USERNAME=user                // Set the msql username for your configuration
DB_PASSWORD=password            // Set the password
```

Run ```php artisan key:generate```

Run ```php artisan migrate```

**Warning:** The database must be created in mysql before running this command.


### Launch

Run ```php artisan serve``` to start a local php server

The application is now available on [http://localhost:8000](http://localhost:8000)


You can view a list of product from the Appliances Delivered dishwashers section.

You can register and log in.

When you are logged in, you add and remove products from your wish list.