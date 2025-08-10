{{{header}}}

<header>
        <h1>My Blog</h1>
        <button id="createPostBtn">Create New Post</button>
    </header>

    <main>
        <div id="postsContainer"></div>
    </main>

    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeCreatePostBtn">&times;</span>
            <h2>Create New Blog Post</h2>
            <input type="text" id="postTitle" placeholder="Post Title">
            <textarea id="postContent" placeholder="Post Content"></textarea>
            <button id="savePostBtn">Save Post</button>
        </div>
    </div>

    <div id="editPostModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeEditPostBtn">&times;</span>
            <h2>Edit Blog Post</h2>
            <input type="text" id="editPostTitle" placeholder="Post Title">
            <textarea id="editPostContent" placeholder="Post Content"></textarea>
            <button id="updatePostBtn">Update Post</button>
        </div>
    </div>

{{{footer}}}