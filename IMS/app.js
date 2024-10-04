// Import required modules
const express = require('express');
const bodyParser = require('body-parser');
const session = require('express-session');
const path = require('path');
const bcrypt = require('bcrypt');
const sql = require('mssql');

// Initialize express app
const app = express();

// Middleware for parsing form data
app.use(bodyParser.urlencoded({ extended: true }));

// Set up session
app.use(session({
  secret: 'yourSecretKey',
  resave: false,
  saveUninitialized: true,
}));

// Serve static files (CSS, images, etc.)
app.use(express.static(path.join(__dirname, 'public')));  // Update this to the correct static folder

// Database configuration for SQL Server with Windows Authentication
const dbConfig = {
  server: 'SUJAN_OMEN\\SQLEXPRESS', // Your SQL Server instance
  database: 'IMS_DB',               // Your database name
  driver: 'msnodesqlv8',            // Driver for Windows Authentication
  options: {
    trustedConnection: true,        // Use Windows Authentication
    enableArithAbort: true,        // Ensure arithmetic abort is enabled
    port: 1433,                    // Specify the port if necessary
  }
};

// Route to serve the login page
app.get('/login', (req, res) => {
  res.sendFile(path.join(__dirname, 'login.html'));  // Ensure the path is correct
});

// Route to serve the signup (create-account) page
app.get('/signup', (req, res) => {
  res.sendFile(path.join(__dirname, 'create-account.html'));  // Ensure the path is correct
});

// Route to handle user signup
app.post('/signup', async (req, res) => {
  const { fullname, email, password } = req.body;

  // Validate password match
  const confirmPassword = req.body['confirm-password'];
  if (password !== confirmPassword) {
    return res.status(400).send('Passwords do not match!');
  }

  try {
    // Hash the password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Connect to the database
    await sql.connect(dbConfig);

    // Insert the user into the database
    const query = `INSERT INTO Users (Fullname, Email, Password) VALUES (@fullname, @Email, @Password)`;
    const request = new sql.Request();
    request.input('fullname', sql.NVarChar, fullname);
    request.input('Email', sql.NVarChar, email);
    request.input('Password', sql.NVarChar, hashedPassword);

    // Execute the query
    request.query(query, (err, result) => {
      if (err) {
        console.error('Error inserting user into database:', err);
        return res.status(500).send('Error creating account.');
      }

      // Successful account creation
      res.redirect('/login');  // Redirect to login page after successful signup
    });
  } catch (error) {
    console.error('Error during signup:', error);
    res.status(500).send('Server error');
  }
});

// Route to handle user login
app.post('/login', async (req, res) => {
  const { email, password } = req.body;

  try {
    // Connect to the database
    await sql.connect(dbConfig);

    // Query to find the user by email
    const query = `SELECT * FROM Users WHERE Email = @Email`;
    const request = new sql.Request();
    request.input('Email', sql.NVarChar, email);

    // Execute the query
    request.query(query, async (err, result) => {
      if (err) {
        console.error('Error querying database:', err);
        return res.status(500).send('Login failed.');
      }

      // Check if the user exists
      const user = result.recordset[0];
      if (!user) {
        return res.status(400).send('User not found.');
      }

      // Check if the password is correct
      const isMatch = await bcrypt.compare(password, user.Password);
      if (!isMatch) {
        return res.status(400).send('Incorrect password.');
      }

      // Set session and redirect to dashboard
      req.session.username = user.Fullname;
      res.redirect('/dashboard');
    });
  } catch (error) {
    console.error('Error during login:', error);
    res.status(500).send('Server error');
  }
});

// Route to handle the dashboard (require login)
app.get('/dashboard', (req, res) => {
  if (req.session.username) {
    res.sendFile(path.join(__dirname, 'IMS', 'views', 'dashboard.html'));  // Ensure the path is correct
  } else {
    res.redirect('/login');
  }
});

// Start the server
app.listen(3000, () => {
  console.log('Server is running on http://localhost:3000');
});
