<div id="top"></div>
<div align="right">

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/48b3eef92a06470a86c89ffcd62ca399)](https://www.codacy.com/gh/siakamansaly/REST-API-with-Symfony/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=siakamansaly/REST-API-with-Symfony&amp;utm_campaign=Badge_Grade)

</div>
<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/siakamansaly/REST-API-with-Symfony">
    <img src="public/images/logo_blue.png" alt="Logo">
  </a>
  <h1 align="center">REST-API-with-Symfony</h1>
  <p align="center">
    BileMo
  </p>
</div>

<!-- ABOUT THE PROJECT -->
## About The Project

<div align="center">
    <img src="public/images/Screenshot.png" alt="Screenshot" width="700px">
</div>
<p>Creation of a REST API allowing a customer to expose BileMo products to its users</p>
<p>The project contains:</p>
<ul>
  <li>a link to the API documentation</li>
  <li>a endpoint to connection</li>
  <li>endpoints to view products</li>
  <li>endpoints to manage users</li>
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- Built With -->
## Built With

This section list the main frameworks/libraries used to start your project.
<ul>
  <li><a href="https://symfony.com/doc/5.4/index.html" target="_blank">Symfony 5.4.7</a></li>
  <li><a href="https://api-platform.com/" target="_blank">API Platform</a></li>
  <li><a href="https://jwt.io/" target="_blank">JWT</a></li>
  <li><a href="https://getbootstrap.com/" target="_blank">Bootstrap</a></li>
  <li><a href="https://jquery.com" target="_blank">JQuery</a></li>
  <li><a href="https://www.php.net/" target="_blank">PHP</a></li>
  <li><a href="https://www.mysql.com/fr/">MySQL</a></li>
  <li><a href="https://twig.symfony.com/" target="_blank">Twig</a></li>
  <li><a href="https://getcomposer.org/" target="_blank">Composer</a></li>
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- Prerequisites -->
## Prerequisites

This is the list of things you need to use the software.
   ```sh
      - PHP: >=7.2.5
      - MySQL
      - Composer
      - OpenSSL
   ```
<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple example steps :

1.&nbsp;Clone the repo **REST-API-with-Symfony**
   ```sh
   git clone https://github.com/siakamansaly/REST-API-with-Symfony.git
   ```

2.&nbsp;Install composer packages
   ```sh
   cd REST-API-with-Symfony
   composer install
   ```
3.&nbsp;You customize variables of file **.env** as needed to run the environment.
   ```sh
   DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
   JWT_PASSPHRASE=EnterYourPassPhraseHere # required for token creation
   ```
4.&nbsp;Create database
   ```sh
   php bin/console doctrine:database:create
   ```
   ```sh
   php bin/console doctrine:migrations:migrate
   ```
5.&nbsp;Load fixtures
   ```sh
   php bin/console doctrine:fixtures:load
   ```
6.&nbsp;Generate the SSL keys
   ```sh
   php bin/console lexik:jwt:generate-keypair
   ```
7.&nbsp;Run project
   ```sh
   php -S localhost:8000 -t public/
   ```  
8.&nbsp;Log in with an account :

   Administrator account
   ```sh
   -Username : admin@example.fr
   -Password : password
   ```
   Customer account
   ```sh
   -Username : customer1@example.fr
   -Password : password
   ```
9.&nbsp;Documentation link : http://localhost:8000/api


<p align="right">(<a href="#top">back to top</a>)</p>

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1.&nbsp;Fork the Project

2.&nbsp;Create your Feature Branch (`git checkout -b feature/NewFeature`)

3.&nbsp;Commit your Changes (`git commit -m 'Add some NewFeature'`)

4.&nbsp;Push to the Branch (`git push origin feature/NewFeature`)

5.&nbsp;Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- CONTACT -->
## Contact

Siaka MANSALY : [siaka.mansaly@gmail.com](siaka.mansaly@gmail.com) 

LinkedIn : [https://www.linkedin.com/in/siaka-mansaly/](https://www.linkedin.com/in/siaka-mansaly/)

Project Link: [https://github.com/siakamansaly/REST-API-with-Symfony](https://github.com/siakamansaly/REST-API-with-Symfony)
              
<p align="right">(<a href="#top">back to top</a>)</p>

## Acknowledgments

Thanks to my mentor [Hamza](https://github.com/Hamzasakrani) for his guidance and support!

<ul>
  <li><a href="https://cs.symfony.com/" target="_blank">PHP CS Fixer</a></li>
  <li><a href="https://phpunit.de/index.html" target="_blank">PHP Unit</a></li>
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>
