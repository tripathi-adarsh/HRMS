<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Performance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder {
    public function run() {
        // Roles
        foreach (['admin','hr','employee'] as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name'=>$role,'guard_name'=>'web']);
        }

        // Admin
        $admin = User::firstOrCreate(['email'=>'admin@hrms.com'], [
            'name'=>'Admin User','password'=>Hash::make('password'),'role'=>'admin'
        ]);
        $admin->assignRole('admin');

        // HR
        $hr = User::firstOrCreate(['email'=>'hr@hrms.com'], [
            'name'=>'Sarah Johnson','password'=>Hash::make('password'),'role'=>'hr'
        ]);
        $hr->assignRole('hr');

        // Departments
        $deptNames = ['Engineering','Human Resources','Finance','Marketing','Operations','Sales','Design','Legal'];
        $depts = [];
        foreach ($deptNames as $d) {
            $depts[] = Department::firstOrCreate(['name'=>$d]);
        }

        // Designations per department
        $desigData = [
            ['name'=>'Software Engineer',       'department_id'=>$depts[0]->id],
            ['name'=>'Senior Developer',         'department_id'=>$depts[0]->id],
            ['name'=>'Tech Lead',                'department_id'=>$depts[0]->id],
            ['name'=>'DevOps Engineer',          'department_id'=>$depts[0]->id],
            ['name'=>'HR Executive',             'department_id'=>$depts[1]->id],
            ['name'=>'HR Manager',               'department_id'=>$depts[1]->id],
            ['name'=>'Accountant',               'department_id'=>$depts[2]->id],
            ['name'=>'Finance Manager',          'department_id'=>$depts[2]->id],
            ['name'=>'Marketing Executive',      'department_id'=>$depts[3]->id],
            ['name'=>'Marketing Manager',        'department_id'=>$depts[3]->id],
            ['name'=>'Operations Manager',       'department_id'=>$depts[4]->id],
            ['name'=>'Operations Analyst',       'department_id'=>$depts[4]->id],
            ['name'=>'Sales Executive',          'department_id'=>$depts[5]->id],
            ['name'=>'Sales Manager',            'department_id'=>$depts[5]->id],
            ['name'=>'UI/UX Designer',           'department_id'=>$depts[6]->id],
            ['name'=>'Graphic Designer',         'department_id'=>$depts[6]->id],
            ['name'=>'Legal Advisor',            'department_id'=>$depts[7]->id],
        ];
        $desigs = [];
        foreach ($desigData as $d) {
            $desigs[] = Designation::firstOrCreate(['name'=>$d['name']], $d);
        }

        // Leave Types
        $leaveTypes = [
            ['name'=>'Casual Leave',    'days_allowed'=>12],
            ['name'=>'Sick Leave',      'days_allowed'=>10],
            ['name'=>'Paid Leave',      'days_allowed'=>15],
            ['name'=>'Maternity Leave', 'days_allowed'=>90],
            ['name'=>'Paternity Leave', 'days_allowed'=>15],
            ['name'=>'Emergency Leave', 'days_allowed'=>5],
        ];
        $ltIds = [];
        foreach ($leaveTypes as $lt) {
            $ltIds[] = LeaveType::firstOrCreate(['name'=>$lt['name']], $lt)->id;
        }

        // Fake employees
        $employeeData = [
            ['name'=>'Arjun Sharma',    'email'=>'arjun.sharma@company.com',    'phone'=>'9876543210','gender'=>'male',  'dept'=>0,'desig'=>0,'salary'=>75000],
            ['name'=>'Priya Patel',     'email'=>'priya.patel@company.com',     'phone'=>'9876543211','gender'=>'female','dept'=>0,'desig'=>1,'salary'=>95000],
            ['name'=>'Rahul Verma',     'email'=>'rahul.verma@company.com',     'phone'=>'9876543212','gender'=>'male',  'dept'=>0,'desig'=>2,'salary'=>120000],
            ['name'=>'Sneha Gupta',     'email'=>'sneha.gupta@company.com',     'phone'=>'9876543213','gender'=>'female','dept'=>0,'desig'=>3,'salary'=>85000],
            ['name'=>'Vikram Singh',    'email'=>'vikram.singh@company.com',    'phone'=>'9876543214','gender'=>'male',  'dept'=>1,'desig'=>4,'salary'=>65000],
            ['name'=>'Anita Desai',     'email'=>'anita.desai@company.com',     'phone'=>'9876543215','gender'=>'female','dept'=>1,'desig'=>5,'salary'=>80000],
            ['name'=>'Suresh Kumar',    'email'=>'suresh.kumar@company.com',    'phone'=>'9876543216','gender'=>'male',  'dept'=>2,'desig'=>6,'salary'=>70000],
            ['name'=>'Meera Nair',      'email'=>'meera.nair@company.com',      'phone'=>'9876543217','gender'=>'female','dept'=>2,'desig'=>7,'salary'=>90000],
            ['name'=>'Kiran Reddy',     'email'=>'kiran.reddy@company.com',     'phone'=>'9876543218','gender'=>'male',  'dept'=>3,'desig'=>8,'salary'=>68000],
            ['name'=>'Pooja Mehta',     'email'=>'pooja.mehta@company.com',     'phone'=>'9876543219','gender'=>'female','dept'=>3,'desig'=>9,'salary'=>88000],
            ['name'=>'Arun Joshi',      'email'=>'arun.joshi@company.com',      'phone'=>'9876543220','gender'=>'male',  'dept'=>4,'desig'=>10,'salary'=>92000],
            ['name'=>'Divya Rao',       'email'=>'divya.rao@company.com',       'phone'=>'9876543221','gender'=>'female','dept'=>4,'desig'=>11,'salary'=>72000],
            ['name'=>'Manoj Tiwari',    'email'=>'manoj.tiwari@company.com',    'phone'=>'9876543222','gender'=>'male',  'dept'=>5,'desig'=>12,'salary'=>62000],
            ['name'=>'Kavita Sharma',   'email'=>'kavita.sharma@company.com',   'phone'=>'9876543223','gender'=>'female','dept'=>5,'desig'=>13,'salary'=>85000],
            ['name'=>'Rohit Agarwal',   'email'=>'rohit.agarwal@company.com',   'phone'=>'9876543224','gender'=>'male',  'dept'=>6,'desig'=>14,'salary'=>78000],
            ['name'=>'Nisha Kapoor',    'email'=>'nisha.kapoor@company.com',    'phone'=>'9876543225','gender'=>'female','dept'=>6,'desig'=>15,'salary'=>74000],
            ['name'=>'Sanjay Mishra',   'email'=>'sanjay.mishra@company.com',   'phone'=>'9876543226','gender'=>'male',  'dept'=>7,'desig'=>16,'salary'=>110000],
            ['name'=>'Ritu Bhatia',     'email'=>'ritu.bhatia@company.com',     'phone'=>'9876543227','gender'=>'female','dept'=>0,'desig'=>0,'salary'=>72000],
            ['name'=>'Deepak Pandey',   'email'=>'deepak.pandey@company.com',   'phone'=>'9876543228','gender'=>'male',  'dept'=>0,'desig'=>1,'salary'=>98000],
            ['name'=>'Sunita Yadav',    'email'=>'sunita.yadav@company.com',    'phone'=>'9876543229','gender'=>'female','dept'=>3,'desig'=>8,'salary'=>66000],
        ];

        $employees = [];
        $joiningDates = [
            '2021-03-15','2020-07-01','2019-11-20','2022-01-10','2021-06-05',
            '2020-09-15','2022-04-01','2021-12-01','2023-02-14','2022-08-20',
            '2020-05-10','2021-10-25','2023-01-05','2022-06-15','2021-08-30',
            '2023-03-01','2020-11-11','2022-03-20','2021-07-07','2023-05-15',
        ];

        foreach ($employeeData as $i => $ed) {
            $user = User::firstOrCreate(['email'=>$ed['email']], [
                'name'=>$ed['name'],
                'password'=>Hash::make('password'),
                'role'=>'employee',
            ]);
            $user->assignRole('employee');

            $emp = Employee::firstOrCreate(['email'=>$ed['email']], [
                'user_id'        => $user->id,
                'employee_id'    => 'EMP'.str_pad($i+1, 4, '0', STR_PAD_LEFT),
                'name'           => $ed['name'],
                'phone'          => $ed['phone'],
                'department_id'  => $depts[$ed['dept']]->id,
                'designation_id' => $desigs[$ed['desig']]->id,
                'salary'         => $ed['salary'],
                'joining_date'   => $joiningDates[$i],
                'gender'         => $ed['gender'],
                'dob'            => Carbon::parse($joiningDates[$i])->subYears(rand(25,40))->format('Y-m-d'),
                'address'        => rand(1,99).' '.['MG Road','Park Street','Nehru Nagar','Gandhi Marg','Lal Bagh'][rand(0,4)].', Bangalore',
                'status'         => 'active',
            ]);
            $employees[] = $emp;
        }

        // Attendance — last 30 days
        $statuses = ['present','present','present','present','present','late','absent','half_day'];
        $punchIns = ['09:00','09:05','09:15','09:30','08:55','10:05','10:30'];
        foreach ($employees as $emp) {
            for ($d = 29; $d >= 0; $d--) {
                $date = Carbon::today()->subDays($d);
                if ($date->isWeekend()) continue;
                $status = $statuses[array_rand($statuses)];
                $punchIn = $status !== 'absent' ? $punchIns[array_rand($punchIns)] : null;
                $punchOut = $punchIn ? Carbon::parse($punchIn)->addHours(rand(7,9))->format('H:i') : null;
                Attendance::firstOrCreate(
                    ['employee_id'=>$emp->id, 'date'=>$date->format('Y-m-d')],
                    ['status'=>$status, 'punch_in'=>$punchIn, 'punch_out'=>$punchOut]
                );
            }
        }

        // Leaves
        $leaveReasons = [
            'Family function','Medical appointment','Personal work','Vacation trip',
            'Fever and cold','Wedding ceremony','Home renovation','Child care',
        ];
        $leaveStatuses = ['approved','approved','pending','rejected'];
        foreach ($employees as $i => $emp) {
            for ($l = 0; $l < 3; $l++) {
                $from = Carbon::today()->subDays(rand(5, 60));
                $days = rand(1, 3);
                $to   = $from->copy()->addDays($days - 1);
                $status = $leaveStatuses[array_rand($leaveStatuses)];
                Leave::firstOrCreate(
                    ['employee_id'=>$emp->id, 'from_date'=>$from->format('Y-m-d'), 'leave_type_id'=>$ltIds[array_rand($ltIds)]],
                    [
                        'to_date'  => $to->format('Y-m-d'),
                        'days'     => $days,
                        'reason'   => $leaveReasons[array_rand($leaveReasons)],
                        'status'   => $status,
                        'remarks'  => $status === 'rejected' ? 'Insufficient leave balance' : null,
                    ]
                );
            }
        }

        // Payroll — last 3 months
        $now = Carbon::now();
        foreach ($employees as $emp) {
            for ($m = 0; $m < 3; $m++) {
                $month = $now->copy()->subMonths($m);
                $bonus     = rand(0, 1) ? rand(2000, 8000) : 0;
                $deduction = rand(500, 3000);
                $net       = $emp->salary + $bonus - $deduction;
                Payroll::firstOrCreate(
                    ['employee_id'=>$emp->id, 'month'=>$month->month, 'year'=>$month->year],
                    [
                        'basic_salary' => $emp->salary,
                        'bonus'        => $bonus,
                        'deduction'    => $deduction,
                        'net_salary'   => $net,
                        'status'       => $m > 0 ? 'paid' : 'pending',
                    ]
                );
            }
        }

        // Performance
        $tasks = [
            'Q1 Performance Review','Project Alpha Delivery','Client Presentation','Code Review',
            'System Architecture Design','Team Training Session','Annual Appraisal','Sprint Planning',
            'Product Launch','Database Optimization','Security Audit','UI Redesign',
        ];
        $reviews = [
            'Excellent work, exceeded expectations.',
            'Good performance, met all targets.',
            'Needs improvement in communication.',
            'Outstanding contribution to the team.',
            'Consistent and reliable performer.',
        ];
        $perfStatuses = ['assigned','in_progress','completed','completed'];
        foreach ($employees as $emp) {
            for ($p = 0; $p < 2; $p++) {
                $status = $perfStatuses[array_rand($perfStatuses)];
                Performance::create([
                    'employee_id'  => $emp->id,
                    'task'         => $tasks[array_rand($tasks)],
                    'description'  => 'Detailed evaluation of employee performance for the review period.',
                    'rating'       => $status === 'completed' ? rand(3, 5) : null,
                    'review'       => $status === 'completed' ? $reviews[array_rand($reviews)] : null,
                    'review_date'  => $status === 'completed' ? Carbon::today()->subDays(rand(1,30))->format('Y-m-d') : null,
                    'status'       => $status,
                ]);
            }
        }

        echo "✅ Seeding complete! 20 employees, attendance, leaves, payroll, and performance data created.\n";
        echo "   Admin: admin@hrms.com / password\n";
        echo "   HR:    hr@hrms.com / password\n";
        echo "   Employee: arjun.sharma@company.com / password\n";
    }
}
