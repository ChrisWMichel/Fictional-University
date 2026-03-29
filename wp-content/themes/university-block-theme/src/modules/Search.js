import $ from "jquery"

class search {
    constructor() {
        this.addSearchHTML();
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
            this.showOverlay();
        })

        $(document).on("keydown", e => {
            if (e.ctrlKey && e.key === "s") {
                e.preventDefault()
                this.showOverlay()
            }
        })
    }

    showOverlay() {
        $(".search-overlay").addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val("");
        setTimeout(() => document.getElementById("search-term").focus(), 50);
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
                }, 750)
            } else{
                this.resultsDiv.html("");
                this.isSpinnerVisible = false;
            }
        }  
        this.previousValue = this.searchField.val();
    }

    getResults() {
        $.ajax({
            url: "/wp-json/university/v1/search?term=" + encodeURIComponent(this.searchField.val()),
            type: "GET",
            success: (data) => {
                this.resultsDiv.html(`
                    <div class="row">
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">General Information</h2>
                        ${data.generalInfo.length ? `<ul class="link-list min-list">${data.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''} </li>`).join("")}</ul>` : `<p>No general information matches that search.</p>`}
                    </div>
                     <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>                       
                        ${data.programs.length ? `<ul class="link-list min-list">${data.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}</ul>` : `<p>No programs matches that search. View all <a href="/programs">programs</a></p>`} 

                         <h2 class="search-overlay__section-title">Professors</h2>
                        ${data.professors.length ? `<ul class="professor-cards">${data.professors.map(item => `<li class="professor-card__list-item">
                            <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image" src="${item.image}" alt="A picture of ${item.title}">
                                <span class="professor-card__name">${item.title}</span>
                                
                            </a>
                            </li>`).join("")}</ul>` : `<p>No professors matches that search.</p>`}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>                        
                        ${data.campuses.length ? `<ul class="link-list min-list">${data.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}</ul>` : `<p>No campuses matches that search.</p>`}

                        <h2 class="search-overlay__section-title">Events</h2>
                        ${data.events.length ? `${data.events.map(item => `
                            <div class="event-summary" >
                        <a class="event-summary__date t-center" href="${item.permalink}">
                            <span class="event-summary__month">${item.eventMonth}</span>
                            <span class="event-summary__day">${item.eventDay}</span>
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                            <p>${item.excerpt} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                        </div>
                        <div class="generic-content">
                        </div>
                    </div>
                            `).join("")}` : `<p>No events matches that search.</p>`}
                    </div>
                   
                </div>
            `);
             this.isSpinnerVisible = false;
            },error: () => {
            this.resultsDiv.html("<p>Something went wrong. Please try again.</p>");
            this.isSpinnerVisible = false;
            }
        })
       
    }

    addSearchHTML() {
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                    <form action="${universityData.root_url}" method="get">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" name="s" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
                    </form>
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div class="search-overlay__results"></div>
                </div>
            </div>
            `);
    }
}

export default search