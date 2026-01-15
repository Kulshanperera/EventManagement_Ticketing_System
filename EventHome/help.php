<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Help & Contact - Event Garden</title>
<link rel="stylesheet" href="../Eventcss/homePage.css">
<link rel="stylesheet" href="../Eventcss/adminDashboard.css">
<link rel="stylesheet" href="../Eventcss/help.css">
<style>

</style>
</head>
<body class="back">

    <header>
        <nav class="navbar">
            <a href="../homePage.php" class="logo">Event Garden</a>
            <div class="user-info">
                    <a href="homePage.php" class="browse-btn">Home</a>
            </div>
        </nav>
    </header>

    <div class="help-container">
        <div class="help-header">
            <h1>Help & Contact</h1>
            <p>Get assistance, learn about our services, or contact our support team</p>
        </div>

    
        <!-- Contact Information -->
        <div class="contact-info">
            <h2>Contact Information</h2>
            
            <div class="contact-details">
                <div class="contact-item">
                    <h3><span class="icon">📧</span> General Inquiries</h3>
                    <p><strong>Email:</strong> <a href="mailto:info@eventgarden.com" class="email-link">info@eventgarden.com</a></p>
                    <p><strong>Response Time:</strong> Within 24 hours</p>
                </div>
                
                <div class="contact-item">
                    <h3><span class="icon">📞</span> Customer Support</h3>
                    <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                    <p><strong>Hours:</strong> 9 AM - 6 PM (Mon-Fri)</p>
                    <p><strong>SMS Support:</strong> +1 (555) 987-6543</p>
                </div>
                
                <div class="contact-item">
                    <h3><span class="icon">🏢</span> Event Submissions</h3>
                    <p><strong>Email:</strong> <a href="mailto:events@eventgarden.com" class="email-link">events@eventgarden.com</a></p>
                    <p><strong>Partnerships:</strong> <a href="mailto:partners@eventgarden.com" class="email-link">partners@eventgarden.com</a></p>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="business-hours">
                <h3>Business Hours</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Opening Hours</th>
                            <th>Support Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monday - Friday</td>
                            <td>9:00 AM - 6:00 PM</td>
                            <td>Phone, Email, Live Chat</td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td>10:00 AM - 4:00 PM</td>
                            <td>Email Only</td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td>12:00 PM - 4:00 PM</td>
                            <td>Emergency Support Only</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Emergency Contact -->
            <div class="contact-item emergency" style="margin-top: 30px;">
                <h3><span class="icon">🚨</span> Emergency Support</h3>
                <p><strong>For event-day emergencies or urgent issues:</strong></p>
                <p><strong>Hotline:</strong> +1 (555) 911-0000</p>
                <p><strong>Available:</strong> 24/7 for active events</p>
                <p><small>For technical issues, refunds, or event cancellations</small></p>
            </div>
        </div>


        <!-- Additional Information -->
        <div class="section-card" style="margin-top: 40px;">
            <h2><span class="icon">ℹ️</span> Additional Information</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h3>Physical Address</h3>
                    <p>Event Garden Headquarters<br>
                    No: 35 <br>
                    Reid Avenuebolombo 7,<br>
                    Sri Lanka.</p>
                </div>
                <div>
                    <h3>Social Media</h3>
                    <p>📘 Facebook: facebook.com/eventgarden<br>
                    📸 Instagram: @eventgarden<br>
                    🐦 Twitter: @eventgarden<br>
                    💼 LinkedIn: linkedin.com/company/eventgarden</p>
                </div>
                <div>
                    <h3>Newsletter</h3>
                    <p>Subscribe for event updates and special offers!</p>
                    <form style="margin-top: 10px;">
                        <input type="email" placeholder="Enter your email" style="padding: 10px; width: 70%; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="submit" style="padding: 10px 15px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>