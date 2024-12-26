document.addEventListener('DOMContentLoaded', () => {
    // Function to show the desired section and hide others
    function showSection(sectionId) {
        // Get all sections
        const sections = document.querySelectorAll('.section');
        sections.forEach((section) => {
            // Hide all sections
            section.style.display = 'none';
        });

        // Show the selected section
        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.style.display = 'block';
        }
    }

    // Attach click events to navigation items
    const navItems = document.querySelectorAll('nav ul li');
    navItems.forEach((navItem) => {
        navItem.addEventListener('click', () => {
            const sectionId = navItem.getAttribute('data-section');
            console.log(`Switching to section: ${sectionId}`); // Debugging
            if (sectionId) {
                showSection(sectionId);
            }
        });
    });

    // Show 'hero' section by default
    showSection('hero');



});

// Mobile Responsiveness
document.addEventListener('DOMContentLoaded', () => {
    const menuBar = document.querySelector('.menu');
    const asideMenu = document.querySelector('aside');

    menuBar.addEventListener('click', () => {
        asideMenu.classList.toggle('active');
    });

    // Adding click event listeners to each `li` element with the 'remove-button' class
    const removeButtons = document.querySelectorAll('.remove-button');

    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Close the aside menu by removing the 'active' class or hiding it
            asideMenu.classList.remove('active');
        });
    });
});






// Carousel Functionality

document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    const testimonials = document.querySelectorAll('.testimonial');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    // Check if carousel elements are found
    if (!track || testimonials.length === 0 || !prevButton || !nextButton) {
        console.error("Carousel elements are missing from the DOM.");
        return;
    }

    let currentIndex = 0;

    // Function to update the testimonial width dynamically
    const getTestimonialWidth = () => testimonials[0]?.offsetWidth || 0;

    // Update carousel slide
    const updateCarousel = () => {
        const testimonialWidth = getTestimonialWidth();
        track.style.transform = `translateX(-${currentIndex * testimonialWidth}px)`;
    };

    // Show previous testimonial
    const showPrevTestimonial = () => {
        currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
        updateCarousel();
    };

    // Show next testimonial
    const showNextTestimonial = () => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        updateCarousel();
    };

    // Add event listeners for navigation buttons
    prevButton.addEventListener('click', showPrevTestimonial);
    nextButton.addEventListener('click', showNextTestimonial);

    // Handle window resizing to adjust testimonial width
    window.addEventListener('resize', updateCarousel);

    // Automatic sliding
    const autoSlide = setInterval(showNextTestimonial, 5000);

    // Reset auto-slide on user interaction
    const resetAutoSlide = () => {
        clearInterval(autoSlide);
        setInterval(showNextTestimonial, 5000);
    };

    // Touch events for swipe functionality
    let touchStartX = 0;

    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    track.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) showNextTestimonial();
        if (touchEndX - touchStartX > 50) showPrevTestimonial();
        resetAutoSlide();
    });

    // Initialize the carousel
    updateCarousel();
});


// Contact handling  
const form = document.getElementById('contact-form');
  const submitButton = document.getElementById('submit-button');

  form.addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent the default form submission

    // Change button text to "Sending..."
    submitButton.textContent = 'Sending...';

    // Send the form data to Formspree
    const formData = new FormData(form);

    try {
      const response = await fetch('https://formspree.io/f/mdkorbel', {
        method: 'POST',
        body: formData,
        headers: {
          Accept: 'application/json',
        },
      });

      if (response.ok) {
        // Change button text to "Sent" with a checkmark emoji
        submitButton.textContent = 'Sent ✔️';

        form.reset();
      } else {
        // In case of error, set button text back to "Send Message"
        submitButton.textContent = 'Send Message';
      }
    } catch (error) {
      // Handle errors by reverting button text
      submitButton.textContent = 'Send Message';
    } finally {
      // Revert button text after a short delay (e.g., 2 seconds)
      setTimeout(() => {
        submitButton.textContent = 'Send Message';
      }, 2000); // Adjust the delay as needed
    }
  });

  // Blog Section 

  // This code fetches blogs and displays them
const blogsContainer = document.getElementById('blogsContainer');

fetch('../admin/backend/fetch_blogs.php?blogs=getblog') // Adjust this path if needed
  .then(response => response.json())
  .then(blogs => {
    console.log(blogs);
    blogs.forEach(blog => {
      
      
      const blogPost = document.createElement('div');
      blogPost.classList.add('blog-post');
      blogPost.innerHTML = `
        <img src="admin/${blog.image_path}" alt="${blog.title}" class="blog-image">
        <h3 class="post-title">${blog.title}</h3>
        <p class="post-excerpt">${blog.content.slice(0, 150)}...</p>
        <a href="readmore.php?id=${blog.id}" target="_blank" class="read-more">Read More</a>
      `;
      blogsContainer.appendChild(blogPost);
    });
  })
  .catch(error => console.error('Error fetching blogs:', error));

