import $ from 'jquery';

class Like {
    constructor(){
        this.events();
    }

    events(){
        $(".like-box").on("click", this.clickDispatcher.bind(this));
    }

    clickDispatcher(e){
        const currentLikeBox = $(e.target).closest(".like-box");
        if(currentLikeBox.data('exists') == "yes"){
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox){
        currentLikeBox.text("Liked");  
        $.ajax({
            url: universityData.root_url + "/wp-json/university/v1/manageLike",
            type: "POST",
            data: {
                "professorId": currentLikeBox.data('professor')
            },
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);      
            },
            success: (response) => {
                currentLikeBox.data('exists', "yes");
                let likeCount = parseInt(currentLikeBox.find(".like-count").text(), 10);
                likeCount++;
                currentLikeBox.find(".like-count").text(likeCount);
                currentLikeBox.data('like', response);
            },
            error: (response) => {
                currentLikeBox.text("Error");
                console.log("There was an error creating the like.");
                console.log(response);
            }
        });
    }

    deleteLike(currentLikeBox){
        currentLikeBox.text("Unliked");
        $.ajax({
            url: universityData.root_url + "/wp-json/university/v1/manageLike",
            type: "DELETE",
            data: {
                "professorId": currentLikeBox.data('professor'),
                "like": currentLikeBox.data('like')
            },
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            success: () => {
                currentLikeBox.data('exists', "no");
                var likeCount = parseInt(currentLikeBox.find(".like-count").text(), 10);
                likeCount--;
                currentLikeBox.find(".like-count").text(likeCount);
            },
            error: (response) => {
                currentLikeBox.text("Error");
                console.log("There was an error deleting the like.");
                console.log(response);
            }
        });
    }
}

export default Like;