import "./index.scss"
import {useSelect, useDispatch} from "@wordpress/data"
import { useState, useEffect } from "@wordpress/element"
import apiFetch from "@wordpress/api-fetch"
import { use } from "react"

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "text",
  attributes: {
    professorId: {
      type: "string"
    }
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("");

    useEffect(() => {
      
      if (props.attributes.professorId) {
        updateTheMeta();
        const fetchData = async () => {
          const response = await apiFetch({
            path: `/featuredProfessor/v1/professor-html?professorId=${props.attributes.professorId}`,
            method: "GET"
          });
          setThePreview(response);
        };
        fetchData();
      }
    }, [props.attributes.professorId]);

    useEffect(() =>{
      return () =>{
        updateTheMeta();
      }
    }, [])

    function updateTheMeta() {
      const professorIds = wp.data.select("core/editor")
        .getBlocks()
        .filter(block => block.name === "ourplugin/featured-professor")
        .map(block => block.attributes.professorId)
        .filter((id, index, arr) => id && arr.indexOf(id) === index);
        
      wp.data.dispatch("core/editor").editPost({ meta: { featured_professor_id: professorIds } });
    }
  
  const allProfessors = useSelect((select) => {
    return select("core").getEntityRecords("postType", "professor", { per_page: -1 })
  })
  if(allProfessors === null) return <p>"Loading..."</p>;
  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={(event) => props.setAttributes({ professorId: event.target.value })} value={props.attributes.professorId}>
          <option value="">Select a Professor</option>
          {allProfessors && allProfessors.map((professor) => (
            <option key={professor.id} value={professor.id}>
              {professor.title.rendered}
            </option>
          ))}
        </select>
      </div>
      <div>
        {thePreview ? (
          <div dangerouslySetInnerHTML={{ __html: thePreview }} />
        ) : (
          <p>The HTML preview of the selected professor will appear here.</p>
        )}
      </div>
    </div>
  )
}