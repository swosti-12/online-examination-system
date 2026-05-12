<style>
  .sidebar { width: 100%; background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%); border-bottom: 1px solid #e5e7eb; padding: 10px 16px; box-shadow: 0 8px 24px rgba(79,70,229,0.08), 0 2px 6px rgba(0,0,0,0.04); border-radius: 12px; display:flex; align-items:center; gap:16px; position: sticky; top: 0; z-index: 50; backdrop-filter: saturate(120%) blur(4px); }
  .sidebar h2 { margin: 0; font-size: 1.1rem; color: #4f46e5; white-space:nowrap; }
  .sidebar ul { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 8px; align-items:center; }
  .sidebar li a { display: block; padding: 8px 12px; color: #1f2937; text-decoration: none; border-radius: 10px; border: 1px solid #e5e7eb; background: #ffffff; transition: .18s ease; box-shadow: 0 1px 0 rgba(0,0,0,0.02); }
  .sidebar li a:hover { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; transform: translateY(-1px); box-shadow: 0 6px 14px rgba(79,70,229,0.12); }
  .sidebar li a.active { background: #eef2ff; color: #4338ca; border-color: #c7d2fe; box-shadow: inset 0 0 0 1px #c7d2fe; }
  .sidebar h2::after { content: ""; display:inline-block; height: 16px; width:1px; background:#e5e7eb; margin: 0 6px 0 12px; vertical-align: middle; border-radius: 1px; }
</style>
<div class="sidebar">
  <h2>Admin Panel</h2>
  <ul>
    <li><a href="../dashboard.php">Dashboard</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
       <li><a href="admin_reset_password.php">Reset User Passwords</a></li>
 <li><a href="manage_subjects.php">Manage Subjects</a></li>
    <li><a href="view_results.php">View Results</a></li>
    <li><a href="../logout.php">Logout</a></li>

  </ul>
</div>
<script>
  (function(){
    try {
      var links = document.querySelectorAll('.sidebar a');
      var here = location.pathname.replace(/\\/g,'/');
      links.forEach(function(a){
        var href = a.getAttribute('href');
        if(!href) return;
        var resolved = new URL(href, location.origin + location.pathname).pathname;
        if (here.endsWith(resolved) || here.indexOf(resolved) > -1) {
          a.classList.add('active');
        }
      });
    } catch(e) {}
  })();
</script>
