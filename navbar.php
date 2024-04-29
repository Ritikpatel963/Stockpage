<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <!-- Meta tags  -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <title>Narvariya Abhushan</title>
  <!-- CSS Assets -->
  <link rel="stylesheet" href="css/app.css" />
  <link rel="stylesheet" href="bootstrap-5/css/bootstrap.min.css">
  <!-- Javascript Assets -->
  <script src="js/app.js" defer></script>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/" />
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
  <script>
    /**
     * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
     */
    localStorage.getItem("_x_darkMode_on") === "true" &&
      document.documentElement.classList.add("dark");
  </script>
</head>

<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
  <!-- Page Wrapper -->
  <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
    <!-- Sidebar -->
    <div class="sidebar print:hidden">
      <!-- Main Sidebar -->
      <div class="main-sidebar w-200">
        <div class="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
          <!-- Application Logo -->
          <div class="flex pt-4">
            <a href="index.php">
              <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg]" src="../NARBARIY.png" alt="logo" />
            </a>
          </div>
          <!-- Main Sections Links -->
          <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: darkblue;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover {
            background-color: #d9d9dd3b;
            color: darkblue;
            border-radius: 5%;
        }

        /* Submenu Styles */
        .submenu {
            display: none;
            position: relative;
            background-color: #f3f3f3;
        }

        .submenu.active {
            display: block;
        }

        .submenu li {
            float: none;
        }
    </style>
          </style>
          <div class="is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6" style="padding-top: 7.5rem !important;">
            <!-- Sales(Gst) -->
            <ul>
              <li>
                <a href="#">Sales (GST) &#8964;</a>
                <ul class="submenu">
                  <li><a href="index.php">New Bill</a></li>
                  <li><a href="todaybill.php">Today's Record</a></li>
                  <li><a href="statement.php">Statement</a></li>
                  <li><a href="d_statement.php">Pending Bills</a></li>
                  <li><a href="payment.php">Payment</a></li>
                </ul>
              </li>
            </ul>

            <ul>
              <li><a href="stock.php">Stock</a></li>
            </ul>
            <!-- Estimate-->
            <ul>
              <li>
                <a href="#">Estimate &#8964;</a>
                <ul class="submenu">
                  <li><a href="estimate/index.php">New Bill</a></li>
                  <li><a href="estimate/todaybill.php">Today's Record</a></li>
                  <li><a href="estimate/statement.php">Statement</a></li>
                  <li><a href="estimate/d_statement.php">Pending Bills</a></li>
                  <li><a href="estimate/payment.php">Payment</a></li>
                </ul>
              </li>
            </ul>
            <!-- Finance -->
            <ul>
              <li><a href="">Finance &#8964;</a>
                <ul class="submenu">
                  <li><a href="finance/index.php">New Registration</a></li>
                  <li><a href="finance/records.php">Records</a></li>
                  <li><a href="finance/statement.php">Statement</a></li>
                  <li><a href="finance/d_statement.php">Pending Bills</a></li>
                  <li><a href="finance/payment.php">Payment</a></li>
                  <li><a href="finance/charges.php">Extra Loan</a></li>
                </ul>
              </li>
            </ul>
            <!-- Expence -->
            <ul>
              <li><a href="register/register_party.php">Register Party</a>
              </li>
            </ul>
            <ul>
              <li><a href="expenses/expenses.php">Expenses &#8964;</a>
                <ul class="submenu">
                  <li><a href="expenses/expenses.php">Enter Expenses</a></li>
                  <li><a href="expenses/today.php">Today's Record</a></li>
                  <li><a href="expenses/search.php">Search Record</a></li>
                  <li><a href="expenses/all_records.php">All Record</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- App Header Wrapper-->
    <nav class="header before:bg-white dark:before:bg-navy-750 print:hidden no_print">
      <!-- App Header  -->
      <div class="header-container relative flex w-full bg-white dark:bg-navy-750 print:hidden" style="text-align: center;display: grid; align-content: center;">
        <!-- Header Items -->
        <p style="font-size: 40px;font-weight: 900;color: darkblue;margin:0">नरवरिया आभूषण</p>
        <div class="flex w-full items-center justify-between">
          <!-- Left: Sidebar Toggle Button -->
          <!-- <p style="font-size: 40px;font-weight: 900;color: darkblue;margin:0">नरवरिया आभूषण</p> -->

          <!-- Right: Header buttons -->
          <div class="-mr-1.5 flex items-center space-x-2 d-none">

            <!-- Mobile Search Toggle -->
            <button @click="$store.global.isSearchbarActive = !$store.global.isSearchbarActive" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 sm:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5 text-slate-500 dark:text-navy-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>

            <!-- Main Searchbar -->
          </div>
        </div>
      </div>
    </nav>
    <!-- Right Sidebar -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all the parent list items
        var parentItems = document.querySelectorAll('ul > li');

        // Loop through each parent item and attach a click event listener
        parentItems.forEach(function(item) {
            item.addEventListener('click', function() {
                // Toggle the active class on the submenu of the clicked parent item
                var submenu = this.querySelector('.submenu');
                if (submenu) {
                    submenu.classList.toggle('active');
                }
            });
        });
    });
</script>
    <script>
      window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
