ប្រព័ន្ធគ្រប់គ្រងបុគ្គលិក
ការពិពណ៌នា
Project នេះគឺជាប្រព័ន្ធគ្រប់គ្រងបុគ្គលិកដែលបង្កើតឡើងដោយប្រើ HTML, CSS (Tailwind CSS), JavaScript, PHP, និង MySQL ។ វាអនុញ្ញាតឱ្យមានការគ្រប់គ្រងព័ត៌មានបុគ្គលិក ការគ្រប់គ្រងគណនីអ្នកប្រើប្រាស់ និងការតាមដានរបាយការណ៍ KPI ប្រចាំថ្ងៃ។

លក្ខណៈពិសេស
ការផ្ទៀងផ្ទាត់ភាពត្រឹមត្រូវអ្នកប្រើប្រាស់: ចូលប្រព័ន្ធដោយផ្អែកលើតួនាទី (Manager, HR, IT, Employee, )។

បញ្ជីបុគ្គលិក: បង្ហាញព័ត៌មានលម្អិតបុគ្គលិក (ឈ្មោះ, តួនាទី, នាយកដ្ឋាន, ទំនាក់ទំនង, ប្រាក់ខែ)។

ការគ្រប់គ្រងបុគ្គលិក (សម្រាប់ Manager/HR): បន្ថែម, កែប្រែ, និងលុបកំណត់ត្រាបុគ្គលិក។

ការបង្កើតគណនីអ្នកប្រើប្រាស់ (សម្រាប់ IT): បង្កើតគណនីអ្នកប្រើប្រាស់ថ្មីសម្រាប់បុគ្គលិកដែលមានតួនាទីផ្សេងៗគ្នា។

របាយការណ៍ KPI ប្រចាំថ្ងៃ (សម្រាប់ Employee/Terminal Operation): បុគ្គលិកអាចដាក់របាយការណ៍ KPI ប្រចាំថ្ងៃរបស់ពួកគេ។

របាយការណ៍ KPI សរុប (សម្រាប់ HR): HR អាចមើលពិន្ទុ KPI សរុបរបស់បុគ្គលិកម្នាក់ៗ។

តម្រូវការជាមុន
ដើម្បីដំណើរការ Project នេះនៅលើកុំព្យូទ័ររបស់អ្នក អ្នកនឹងត្រូវការ៖

WampServer (ឬ XAMPP/Laragon)៖ សម្រាប់បរិស្ថាន PHP និង MySQL ។

Web Browser (Google Chrome, Mozilla Firefox, Microsoft Edge, etc.) ។

ជំហានដំឡើង
អនុវត្តតាមជំហានទាំងនេះដើម្បីដំឡើង និងដំណើរការ Project នេះនៅលើ WampServer របស់អ្នក៖

1. Clone Repository
បើក Git Bash ឬ Command Prompt/Terminal ហើយ clone repository ទៅកាន់ folder www របស់ WampServer របស់អ្នក (ជាធម្មតា C:\wamp64\www\)៖

cd C:\wamp64\www\
git clone https://github.com/Rak87667/Employee_Management.git employee_app

(នេះនឹង clone Project ទៅក្នុង folder ឈ្មោះ employee_app)

2. បង្កើត Database
ចាប់ផ្តើម WampServer របស់អ្នក។ ត្រូវប្រាកដថា Apache និង MySQL កំពុងដំណើរការ។

បើក browser របស់អ្នកហើយចូលទៅកាន់ http://localhost/phpmyadmin/ ។

ចូលដោយប្រើ credentials លំនាំដើម (Username: root, Password: (blank) ឬតាមការកំណត់របស់អ្នក)។

ចុចលើផ្ទាំង "Databases" នៅផ្នែកខាងលើ។

នៅក្នុងវាល "Create database" បញ្ចូលឈ្មោះ employee_db ហើយចុច "Create" ។

3. នាំចូល Database Schema
អ្នកត្រូវនាំចូល tables សម្រាប់ users, employees, និង daily_kpi_reports ។

នៅក្នុង phpMyAdmin ជ្រើសរើស database employee_db ដែលអ្នកទើបតែបានបង្កើតនៅ sidebar ខាងឆ្វេង។

ចុចលើផ្ទាំង "Import" នៅផ្នែកខាងលើ។

ចុច "Choose File" ហើយរកមើលឯកសារ SQL schema របស់អ្នក។ ប្រសិនបើអ្នកមិនទាន់មានទេ អ្នកអាចបង្កើតវាដោយដៃ ឬបង្កើត tables តាមរយៈ SQL commands ។

ដើម្បីបង្កើត tables ដោយដៃ (ប្រសិនបើអ្នកមិនមានឯកសារ SQL):

ចុចលើផ្ទាំង "SQL" នៅក្នុង employee_db ។

បិទភ្ជាប់ SQL commands ខាងក្រោមម្តងមួយៗ ហើយចុច "Go" បន្ទាប់ពីនីមួយៗ៖

សម្រាប់តារាង users:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

សម្រាប់តារាង employees:

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    department VARCHAR(255),
    contact VARCHAR(255),
    salary VARCHAR(255), -- Changed from kpi_summary
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT UNIQUE, -- Link to users table
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

សម្រាប់តារាង daily_kpi_reports:

CREATE TABLE daily_kpi_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    report_date DATE NOT NULL,
    tasks_completed TEXT, -- Stores JSON string of tasks
    kpi_score INT NOT NULL,
    problem_description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

4. បញ្ចូលទិន្នន័យដំបូង (Optional)
អ្នកអាចបញ្ចូលអ្នកប្រើប្រាស់ដំបូង និងបុគ្គលិកដើម្បីសាកល្បង។

សម្រាប់តារាង users:

INSERT INTO users (username, password_hash, role) VALUES
('manager_user', '$2y$10$YourHashedPasswordHere', 'manager'), -- Replace with a real hashed password
('hr_user', '$2y$10$YourHashedPasswordHere', 'hr'),
('it_user', '$2y$10$YourHashedPasswordHere', 'IT'),
('employee_user', '$2y$10$YourHashedPasswordHere', 'employee'),
('terminal_op_user', '$2y$10$YourHashedPasswordHere', 'terminal operation');

(ប្រើ php/hash_generator.php ដើម្បីបង្កើត hashed password)

សម្រាប់តារាង employees:

INSERT INTO employees (name, position, department, contact, salary, user_id) VALUES
('John Doe', 'Manager', 'Management', '123-456-7890', '5000', 1), -- user_id 1 for manager_user
('Jane Smith', 'HR Specialist', 'Human Resources', '098-765-4321', '4500', 2), -- user_id 2 for hr_user
('Seangly', 'Terminal Operation', 'Operations', '012-345-6789', '3000', 5); -- user_id 5 for terminal_op_user

(ត្រូវប្រាកដថា user_id ត្រូវគ្នានឹង id ពីតារាង users របស់អ្នក)

5. កំណត់រចនាសម្ព័ន្ធ Database Connection
បើកឯកសារ employee_app/php/db_config.php នៅក្នុង Text Editor របស់អ្នក។

ត្រូវប្រាកដថាព័ត៌មានលម្អិតនៃការតភ្ជាប់ database របស់អ្នកត្រឹមត្រូវ៖

<?php
// php/db_config.php
$host = 'localhost';
$db = 'employee_db'; // ត្រូវតែត្រូវគ្នានឹងឈ្មោះ database ដែលអ្នកបានបង្កើត
$user = 'root';      // Username របស់ MySQL របស់អ្នក
$pass = '';          // Password របស់ MySQL របស់អ្នក (ទទេសម្រាប់ WampServer លំនាំដើម)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

6. ចូលប្រើ Application
បើក browser របស់អ្នក។

ចូលទៅកាន់ URL ខាងក្រោម៖

http://localhost/employee_app/login.html

ប្រើ credentials របស់អ្នកដើម្បីចូលប្រព័ន្ធ។

ការប្រើប្រាស់
Manager/HR: ចូលមើលបញ្ជីបុគ្គលិក បន្ថែម/កែប្រែ/លុបបុគ្គលិក។ HR ក៏អាចមើលរបាយការណ៍ KPI សរុបរបស់បុគ្គលិកផងដែរ។

IT: ចូលមើលបញ្ជីបុគ្គលិក និងបង្កើតគណនីអ្នកប្រើប្រាស់ថ្មីសម្រាប់តួនាទីផ្សេងៗគ្នា។

Employee/Terminal Operation: ចូលមើលបញ្ជីបុគ្គលិក និងដាក់របាយការណ៍ KPI ប្រចាំថ្ងៃរបស់ពួកគេ។

ការរួមចំណែក
ការរួមចំណែកត្រូវបានស្វាគមន៍! ប្រសិនបើអ្នកចង់រួមចំណែក សូមអនុវត្តតាមជំហានទាំងនេះ៖

Fork repository នេះ។

បង្កើត branch ថ្មីសម្រាប់មុខងាររបស់អ្នក (git checkout -b feature/AmazingFeature) ។

Commit ការផ្លាស់ប្តូររបស់អ្នក (git commit -m 'Add some AmazingFeature') ។

Push ទៅ branch (git push origin feature/AmazingFeature) ។

បើក Pull Request ។
