let posts = []; // This will hold the posts temporarily

// Select elements
const postsContainer = document.getElementById("postsContainer");
const createPostModal = document.getElementById("createPostModal");
const editPostModal = document.getElementById("editPostModal");
const postTitleInput = document.getElementById("postTitle");
const postContentInput = document.getElementById("postContent");
const savePostBtn = document.getElementById("savePostBtn");
const closeCreatePostBtn = document.getElementById("closeCreatePostBtn");
const closeEditPostBtn = document.getElementById("closeEditPostBtn");
const updatePostBtn = document.getElementById("updatePostBtn");

// Open modal to create a post
document.getElementById("createPostBtn").addEventListener("click", () => {
    createPostModal.style.display = "flex";
});

// Close create modal
closeCreatePostBtn.addEventListener("click", () => {
    createPostModal.style.display = "none";
});

// Close edit modal
closeEditPostBtn.addEventListener("click", () => {
    editPostModal.style.display = "none";
});

// Save a new post
savePostBtn.addEventListener("click", () => {
    const newPost = {
        id: Date.now(),
        title: postTitleInput.value,
        content: postContentInput.value
    };
    posts.push(newPost);
    postTitleInput.value = '';
    postContentInput.value = '';
    createPostModal.style.display = "none";
    renderPosts();
});

// Update a post
updatePostBtn.addEventListener("click", () => {
    const updatedPost = {
        id: currentEditPostId,
        title: document.getElementById("editPostTitle").value,
        content: document.getElementById("editPostContent").value
    };
    posts = posts.map(post => (post.id === updatedPost.id ? updatedPost : post));
    renderPosts();
    editPostModal.style.display = "none";
});

// Render posts dynamically
function renderPosts() {
    postsContainer.innerHTML = ''; // Clear the container
    posts.forEach(post => {
        const postElement = document.createElement("div");
        postElement.classList.add("post");
        postElement.innerHTML = `
            <h3>${post.title}</h3>
            <p>${post.content.substring(0, 100)}...</p>
            <button onclick="deletePost(${post.id})">Delete</button>
            <button onclick="editPost(${post.id})">Edit</button>
        `;
        postsContainer.appendChild(postElement);
    });
}

// Delete a post
function deletePost(postId) {
    posts = posts.filter(post => post.id !== postId);
    renderPosts();
}

// Edit a post
let currentEditPostId;
function editPost(postId) {
    currentEditPostId = postId;
    const post = posts.find(p => p.id === postId);
    document.getElementById("editPostTitle").value = post.title;
    document.getElementById("editPostContent").value = post.content;
    editPostModal.style.display = "flex";
}

// Initial render
renderPosts();
