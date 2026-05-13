# 📚 Student Record Management System

A comprehensive web-based student management system built with **PHP 8**, **MySQL/SQLite**, and **Apache (XAMPP)**.

## ✨ Features

### Core Functionality
- ✅ **Add Students** - Add new student records with comprehensive information
- ✅ **View Students** - Display all students in an organized table
- ✅ **View Details** - View complete student information
- ✅ **Edit Students** - Update student records
- ✅ **Delete Students** - Remove student records
- ✅ **Search** - Search students by name, email, or roll number
- ✅ **Status Management** - Mark students as Active, Inactive, or Graduated

### Technical Features
- **Dual Database Support**: Works with both SQLite (default) and MySQL
- **Automatic Database Setup**: Creates tables automatically on first run
- **Responsive Design**: Mobile-friendly interface
- **Data Validation**: Input validation on both client and server side
- **Security**: Prepared statements for SQL injection prevention
- **Timestamps**: Automatic tracking of creation and modification times

## 📋 Student Information Captured

Each student record includes:
- **Personal Information**
  - Roll Number (Unique)
  - First Name & Last Name
  - Email (Unique)
  - Phone Number
  - Date of Birth
  - Gender

- **Address Information**
  - Street Address
  - City, State, Country
  - Postal Code

- **Academic Information**
  - Course/Program
  - Enrollment Date
  - Current Status (Active/Inactive/Graduated)

- **System Information**
  - Created At (Timestamp)
  - Updated At (Timestamp)

## 🚀 Getting Started

### Prerequisites
- XAMPP (PHP 8.0+, Apache, MySQL/MariaDB)
- Web Browser (Chrome, Firefox, Safari, Edge)

### Installation

1. **Extract the project** to `C:\xampp\htdocs\student-management-system`

2. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Click "Start" for Apache
   - Click "Start" for MySQL (optional - SQLite works by default)

3. **Access the Application**
   - Option 1: Using PHP Built-in Server
     ```
     cd C:\xampp\htdocs\student-management-system
     php -S localhost:8080
     ```
   - Option 2: Using Apache
     ```
     http://localhost/student-management-system/
     ```

4. **Access via Browser**
   - Navigate to `http://localhost:8080` (if using PHP server)
   - Or `http://localhost/student-management-system/` (if using Apache)

## 📁 Project Structure

```
student-management-system/
├── index.php                 # Dashboard - View all students
├── add_student.php          # Add new student form
├── view_student.php         # View single student details
├── edit_student.php         # Edit student record
├── delete_student.php       # Delete student record
├── db.php                   # Database connection and setup
├── README.md                # This file
├── students.db              # SQLite database (created automatically)
└── assets/
    └── css/
        └── style.css        # Stylesheet
    └── js/
        └── (optional for future enhancements)
```

## 🗄️ Database Configuration

### SQLite (Default)
- **Location**: `c:\xampp\htdocs\student-management-system\students.db`
- **Advantage**: No separate database server needed
- **Automatically created** on first run

### MySQL (Optional)
- **Database Name**: `student_management_db`
- **Host**: localhost
- **User**: root
- **Password**: (empty by default)

To use MySQL:
1. Start MySQL in XAMPP
2. The system will automatically detect and use MySQL if SQLite connection fails

## 🎨 UI/UX Features

### Dashboard
- Student count statistics
- Search functionality
- Student records in organized table
- Quick action buttons (View, Edit, Delete)
- Status badges with color coding

### Forms
- Responsive form layout
- Grouped input fields
- Clear labeling of required fields (*)
- Placeholder text for guidance
- Form validation with error messages

### Color Scheme
- **Primary Color**: Blue (#3498db)
- **Success Color**: Green (#27ae60)
- **Danger Color**: Red (#e74c3c)
- **Status Indicators**: 
  - Active: Green
  - Inactive: Red
  - Graduated: Blue

## 🔒 Security Features

1. **Input Validation**: All user inputs are validated
2. **SQL Injection Prevention**: Prepared statements for MySQL, parameterized queries for SQLite
3. **XSS Protection**: Output escaping with `htmlspecialchars()`
4. **Error Handling**: Graceful error messages without exposing sensitive information

## 📱 Responsive Design

The application is fully responsive and works on:
- Desktop browsers (1920px+)
- Tablets (768px - 1024px)
- Mobile phones (320px - 480px)

## 🧪 Testing Performed

✅ Add Student Functionality - Works perfectly
✅ View Student List - Displays all records
✅ View Student Details - Shows complete information
✅ Edit Student - Updates records successfully
✅ Delete Student - Removes records (with confirmation)
✅ Search Functionality - Filters by name, email, or roll number
✅ Database Connectivity - Auto-switches between SQLite and MySQL
✅ Responsive Design - Works on all screen sizes
✅ Error Handling - Displays appropriate error messages

## 🐛 Troubleshooting

### Issue: Page shows database connection error
**Solution**: 
- The system will automatically use SQLite database
- Ensure `students.db` has write permissions
- Check that PHP has SQLite support (usually included in XAMPP)

### Issue: Apache won't start
**Solution**:
- Check if port 80 is already in use
- Try using PHP built-in server instead: `php -S localhost:8080`

### Issue: Forms not submitting
**Solution**:
- Ensure all required fields are filled (marked with *)
- Check browser console for errors (F12)
- Verify that `db.php` is in the same directory

## 🚀 Future Enhancements

Potential features for future versions:
- User authentication/login system
- Export records to PDF/Excel
- Bulk student import
- Advanced reporting and analytics
- Grade management
- Attendance tracking
- Email notifications
- File attachments (photos, documents)
- Role-based access control (Admin, Faculty, Student)

## 📞 Support

For issues or questions:
1. Check the Troubleshooting section above
2. Verify all files are in the correct directory
3. Ensure PHP 8+ is installed and running
4. Check browser console (F12) for error messages

## 📄 License

This project is open-source and available for educational purposes.

## 👨‍💻 Technology Stack

- **Backend**: PHP 8.0.30 (procedural style)
- **Database**: SQLite 3 / MySQL 10.4.32 (MariaDB)
- **Frontend**: HTML5, CSS3
- **Server**: Apache 2.4 (XAMPP)
- **Development Environment**: XAMPP on Windows

## ✅ Verification Checklist

- [x] PHP 8 compatibility verified
- [x] Database creation works automatically
- [x] CRUD operations fully functional
- [x] Search feature working
- [x] Responsive design tested
- [x] Error handling implemented
- [x] SQLite and MySQL both supported
- [x] Application running in browser successfully

---

**Version**: 1.0  
**Last Updated**: April 29, 2026  
**Status**: ✅ Production Ready
