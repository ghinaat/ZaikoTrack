function fetchNotifications() {
    // Perform AJAX request
    fetch('/notifikasi/fetch')
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            // Update dropdown menu with new notifications
            const notif = data.data;
            updateDropdownMenu(notif);
    
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}



// Function to update dropdown menu with new notifications
function updateDropdownMenu(data) {
    const dropdownMenu = document.getElementById('notificationDropdown');
    // Clear existing items
    dropdownMenu.innerHTML = '';

    if (data.length === 0) {
        // If there are no notifications, display a message
        const noNotificationsItem = document.createElement('li');
        noNotificationsItem.classList.add('dropdown-item', 'text-center', 'text-muted');
        noNotificationsItem.textContent = 'You have no notifications at this time';
        dropdownMenu.appendChild(noNotificationsItem);
    } else {
        const firstThreeNotifications = data.slice(0, 3);
        
        // Iterate through the first three notifications
        firstThreeNotifications.forEach(notification => {
            const listItem = document.createElement('li');
            listItem.classList.add('mb-2');

            const notificationLink = document.createElement('a');
            notificationLink.classList.add('dropdown-item', 'border-radius-md');
            notificationLink.href = notification.url;

            const notificationContent = document.createElement('div');
            notificationContent.classList.add('d-flex', 'py-1');

            const avatarContainer = document.createElement('div');
            avatarContainer.classList.add('my-auto');
            
            const bellIcon = document.createElement('i');
            bellIcon.classList.add('fas', 'fa-bell', 'me-3'); // Assuming you're using Font Awesome
            
            avatarContainer.appendChild(bellIcon);
            notificationContent.appendChild(avatarContainer);
            
            const textContainer = document.createElement('div');
            textContainer.classList.add('d-flex', 'flex-column', 'justify-content-center');

            const title = document.createElement('h5');
            title.classList.add('text-sm', 'font-weight-normal', 'mb-1');
            title.textContent = notification.title;

            const time = document.createElement('p');
            time.classList.add('text-xs', 'text-secondary', 'mb-0');
            time.innerHTML = `<i class="fa fa-clock me-1"></i> ${notification.time}`;

            textContainer.appendChild(title);
            textContainer.appendChild(time);
            notificationContent.appendChild(textContainer);

            notificationLink.appendChild(notificationContent);
            listItem.appendChild(notificationLink);
            dropdownMenu.appendChild(listItem);
        });
    }

    // Add "See All Notifications" link if necessary
    if (data.length > 0) {
        const seeAllItem = document.createElement('li');
        seeAllItem.classList.add('mb-0');

        const seeAllLink = document.createElement('a');
        seeAllLink.classList.add('dropdown-item', 'border-radius-md');
        seeAllLink.href = '/notifikasi'; // Replace '#' with the actual URL for seeing all notifications
        seeAllLink.textContent = 'See All Notifications';

        seeAllItem.appendChild(seeAllLink);
        dropdownMenu.appendChild(seeAllItem);
    }
}


function fetchAndDisplayNotificationCount() {
    // Perform AJAX request to your `fetch` method
    fetch('/notifikasi/fetch')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            // Extract the count of unread notifications from the response
            const unreadCount = data.unread_count;

            // Update the bell icon badge with the unread notification count
            updateNotificationBadge(unreadCount);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

function updateNotificationBadge(unreadCount) {
    // Get the badge element by its ID
    const badgeElement = document.getElementById('notificationCount');

    // Check if the unread count is greater than 0
    if (unreadCount > 0) {
        // Update the badge text with the unread count
        badgeElement.textContent = unreadCount;
        
        // Display the badge
        badgeElement.style.display = 'inline-block';
        badgeElement.style.borderRadius = '50%'; // Make the badge a circle
    } else {
        // Hide the badge if there are no unread notifications
        badgeElement.style.display = 'none';
    }
}


// Fetch notifications when the page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();
    fetchAndDisplayNotificationCount();

    // Schedule fetching notifications every 30 seconds
    setInterval(fetchNotifications, 30000);
});
