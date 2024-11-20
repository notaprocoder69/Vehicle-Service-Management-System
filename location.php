<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>location</title>
    <style>
        .location-card {
            background-color: #f0f8ff; 
            box-shadow: 0px 0px 10px rgba(77,77,77, 0.9); 
            padding: 20px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            margin-top:80px;
        }

        .location-card h2 {
            color: #333; 
        }

        .location-card p {
            color: #666; 
        }
        .location-card:hover
       {
        background-color:#CCCCFF; 
      }
        
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <?php require 'partials/_nav.php' ?>
  <div class="location-card">
        <h2>Swift & Fix Vehicle Service</h2>
        <p><strong>Address:</strong> 123 JC Road ,Auto City,Banglore,560015</p>
        <p><strong>Contact Information:</strong> Phone: +91 9606556069 | Email:swiftandfix@gmail.com</p>
        <p><strong>Operating Hours:</strong> Monday - Friday: 8:00 AM - 6:00 PM, Saturday: 9:00 AM - 3:00 PM, Sunday: Closed</p>
        <p>For appointments and inquiries, please contact us during our operating hours. We're here to keep your vehicle running smoothly!</p>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>