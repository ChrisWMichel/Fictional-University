import $ from "jquery"

class search {
    constructor() {
        this.resultsDiv = $(".search-overlay__results");
        this.searchField = $("#search-term");
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;
        this.openOverlay();
        this.closeOverlay();
        this.liveSearch();
    }

    openOverlay() {
        $(".js-search-trigger").on("click", () => {
            this.showOverlay()
        })

        $(document).on("keydown", e => {
            if (e.ctrlKey && e.key === "s") {
                e.preventDefault()
                this.showOverlay()
            }
        })
    }

    showOverlay() {
        $(".search-overlay").addClass("search-overlay--active")
        document.getElementById("search-term").focus()
        $("body").addClass("body-no-scroll")
    }

    closeOverlay() {
        $(".search-overlay__close").on("click", () => {
            this.hideOverlay()
        })

        $(document).on("keydown", e => {
            if (e.key === "Escape") {
                this.hideOverlay()
            }
        })
    }

    hideOverlay() {
        $(".search-overlay").removeClass("search-overlay--active")
        $("body").removeClass("body-no-scroll")
    }

    liveSearch() {
        this.searchField.on("keyup", () => this.typingLogic())
    }

    typingLogic(){
        if(this.searchField.val() !== this.previousValue){
            clearTimeout(this.typingTimer)

            if(this.searchField.val()){
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html("<div class='spinner-loader'></div>");
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(() => {
                    this.getResults();
                }, 1000)
            } else{
                this.resultsDiv.html("");
                this.isSpinnerVisible = false;
            }
        }  
        this.previousValue = this.searchField.val();
    }

    getResults() {
        // $.ajax({
        //     url: "/wp-json/wp/v2/search?term=" + this.searchField.val(),
        //     type: "GET",
        //     success: (data) => {
        //         this.isSpinnerVisible = false;
        //         this.resultsDiv.html(data)
        //     }
        // })

        // fetch("/wp-json/wp/v2/search?term=" + this.searchField.val())
        // .then(res => res.json())
        // .then(data => {
        //     this.isSpinnerVisible = false;
        //     this.resultsDiv.html(data)
        // })

        $.getJSON("/wp-json/wp/v2/search?term=" + this.searchField.val(), (data) => {
            this.isSpinnerVisible = false;
            let html = data.map(item => `<li><a href="${item.url}">${item.title}</a></li>`).join("")
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">Search Results</h2>
                <ul class="link-list min-list">${html}</ul>`)
        })
    }
}

export default search