<style>

  .navbar {
      background-color: #007bff !important;
  }

  .navbar-brand {
      font-size: 1.75rem;
      font-weight: bold;
      color: white !important; 
      letter-spacing: 1px;
      text-transform: uppercase;
  }

  .navbar-nav .nav-link {
      padding: 12px 20px; 
      font-weight: bold;  
      font-size: 1.2rem;
      transition: all 0.3s ease;  
  }

  .navbar-nav .nav-link:hover {
      background-color: #0056b3;
      border-radius: 5px;  
  }

  .navbar-nav {
      font-family: 'Arial', sans-serif;
      font-weight: bold;
      letter-spacing: 1px; 
  }

  .navbar-nav {
      justify-content: center !important;
  }
  
  .nav-link {
      font-size: 1.3rem !important;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-primary py-3 shadow-sm">
    <div class="container">

        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white fs-4 fw-bold" href="{{ route('task.index') }}">My Tasks</a>
                </li>
            </ul>

        </div>
        
    </div>
</nav>


