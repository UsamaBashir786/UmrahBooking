<?php
require_once 'connection/connection.php';

// Fetch all users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/css-links.php'; ?>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main flex-1 flex flex-col">
      <!-- Navbar -->
      <div class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <button class="md:hidden text-gray-800" id="menu-btn">
          <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-xl font-semibold"><i class="text-teal-600 fa fa-user mx-2"></i> All Users</h1>
      </div>

      <div class="container mx-auto p-4 sm:p-6">
        <div class="overflow-x-auto bg-white rounded-lg shadow max-h-[500px] overflow-y-auto">
          <!-- For small screens - Card view -->
          <div class="block md:hidden max-h-[500px] overflow-y-auto">
            <div class="space-y-4 p-4">
              <?php while ($user = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow p-4 hover:bg-gray-50">
                  <div class="flex items-center space-x-4 mb-3">
                    <img class="h-10 w-10 rounded-full object-cover"
                      src="../<?php echo htmlspecialchars($user['profile_image']); ?>"
                      alt="User avatar" />

                    <div>
                      <div class="text-sm font-medium text-gray-900">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                      </div>
                      <div class="text-sm text-gray-500">
                        <?php echo htmlspecialchars($user['email']); ?>
                      </div>
                    </div>
                  </div>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-500">Phone:</span>
                      <span class="text-sm text-gray-900">
                        <?php echo htmlspecialchars($user['phone_number']); ?>
                      </span>
                    </div>
                    <div class="flex justify-end space-x-3 mt-3">
                      <button class="text-indigo-600 hover:text-indigo-900"
                        onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                      <button class="text-red-600 hover:text-red-900"
                        onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

          <!-- For medium and larger screens - Table view -->
          <table class="min-w-full hidden md:table">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              // Reset the result pointer
              $result->data_seek(0);
              while ($user = $result->fetch_assoc()):
              ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10">
                        <img class="h-10 w-10 rounded-full object-cover"
                          src="../<?php echo htmlspecialchars($user['profile_image']); ?>"
                          alt="User avatar" />


                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          <?php echo htmlspecialchars($user['full_name']); ?>
                        </div>
                        <div class="text-sm text-gray-500">
                          <?php echo htmlspecialchars($user['gender']); ?>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      <?php echo htmlspecialchars($user['email']); ?>
                    </div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      <?php echo htmlspecialchars($user['phone_number']); ?>
                    </div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"
                      onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                    <button class="text-red-600 hover:text-red-900"
                      onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>


        </div>
      </div>
      <?php include 'includes/js-links.php'; ?>
    </div>
  </div>
  <script>
    function editUser(userId) {
      window.location.href = `edit-user.php?id=${userId}`;
    }

    function deleteUser(userId) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`delete-user.php?id=${userId}`, {
              method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire({
                  title: 'Deleted!',
                  text: 'User has been deleted.',
                  icon: 'success',
                  showConfirmButton: false,
                  timer: 1500
                }).then(() => {
                  window.location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: 'Failed to delete user',
                  icon: 'error',
                  confirmButtonColor: '#3085d6'
                });
              }
            });
        }
      });
    }
  </script>
</body>

</html>