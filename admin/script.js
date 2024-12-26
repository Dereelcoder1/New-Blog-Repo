document.addEventListener('DOMContentLoaded', () => {
    const blogForm = document.getElementById('blogForm');
    const blogContainer = document.getElementById('blogContainer');
  
    blogForm.addEventListener('submit', (e) => {
      e.preventDefault();
  
      // Get form values
      const title = document.getElementById('title').value;
      const content = document.getElementById('content').value;
      const imageInput = document.getElementById('image');
      const isBold = document.getElementById('boldTitle').checked;
  
      // Create a new blog post
      const blogPost = document.createElement('div');
      blogPost.classList.add('blog-post');
  
      // Blog title
      const blogTitle = document.createElement('h3');
      blogTitle.innerText = title;
      if (isBold) {
        blogTitle.style.fontWeight = 'bold';
      }
      blogPost.appendChild(blogTitle);
  
      // Blog image
      if (imageInput.files[0]) {
        const blogImage = document.createElement('img');
        blogImage.src = URL.createObjectURL(imageInput.files[0]);
        blogPost.appendChild(blogImage);
      }
  
      // Blog content
      const blogContent = document.createElement('p');
      blogContent.innerText = content;
      blogPost.appendChild(blogContent);
  
      // Posted by admin
      const postedBy = document.createElement('p');
      postedBy.innerText = 'Posted by Admin';
      postedBy.style.fontStyle = 'italic';
      blogPost.appendChild(postedBy);
  
      // Actions (Edit and Delete)
      const actions = document.createElement('div');
      actions.classList.add('actions');
  
      const editButton = document.createElement('button');
      editButton.innerText = 'Edit';
      editButton.addEventListener('click', () => {
        // Load blog data into the form for editing
        document.getElementById('title').value = title;
        document.getElementById('content').value = content;
        document.getElementById('boldTitle').checked = isBold;
        blogContainer.removeChild(blogPost);
      });
  
      const deleteButton = document.createElement('button');
      deleteButton.innerText = 'Delete';
      deleteButton.addEventListener('click', () => {
        blogContainer.removeChild(blogPost);
      });
  
      actions.appendChild(editButton);
      actions.appendChild(deleteButton);
      blogPost.appendChild(actions);
  
      // Add the blog post to the blog container
      blogContainer.appendChild(blogPost);
  
      // Reset the form
      blogForm.reset();
    });
  });
  