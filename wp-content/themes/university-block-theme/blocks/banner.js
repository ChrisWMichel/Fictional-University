wp.blocks.registerBlockType('university-block-theme/banner', {
    title: 'Banner',
    icon: 'cover-image',
    category: 'layout',
    edit: EditComponent,
    save: SaveComponent

});

function EditComponent() {
    // JSX
    return (
           <div className="page-banner">
                <div className="page-banner__bg-image" style={{ backgroundImage: `url(${bannerData.heroImageUrl})` }}></div>
                <div className="page-banner__content container t-center c-white">
                    <h1 className="headline headline--large">Welcome!</h1>
                    <h2 className="headline headline--medium">This is the block theme banner.</h2>
                    <h3 className="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
                    <a href={bannerData.programsUrl} className="btn btn--large btn--blue">Find Your Major</a>
                </div>
            </div>
    );
}

function SaveComponent() {
    return null;
}