import { createRoot } from '@wordpress/element';
import './frontend.scss';
import { Icon } from '@wordpress/components';
import { useState } from 'react';

const divsToUpdate = document.querySelectorAll('.paying-attention-quiz-update');

divsToUpdate.forEach(div => {
    const data = JSON.parse(div.querySelector('pre').textContent);
    div.innerHTML = '';
    createRoot(div).render(<Quiz {...data} />);

    
});

function Quiz(data) {
    const [isCorrect, setIsCorrect] = useState(null);
    const [selectedIndex, setSelectedIndex] = useState(null);

    function handleAnswer(index) {
        setSelectedIndex(index);
        if (index === data.correctAnswerIndex) {
            setIsCorrect(true);
        } else {
            setIsCorrect(false);
        }
    }

    return (
        <div className="paying-attention-frontend" style={{backgroundColor: data.bgColor, textAlign: data.theAlignment}}>
             <p>{data.question}</p> 
                <ul>
                    {data.answers.map((answer, index) => (
                        <li key={index} className={(isCorrect === true && index === data.correctAnswerIndex ? "no-click" : "") + (isCorrect === true && index !== data.correctAnswerIndex ? " fade-incorrect": "")} onClick={isCorrect === true ? undefined : () => handleAnswer(index)}>
                            
                            {selectedIndex === index && selectedIndex === data.correctAnswerIndex && (
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"  className="bi bi-check-lg" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                                </svg>
                            )}
                             {selectedIndex === index && selectedIndex !== data.correctAnswerIndex && (
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style={{fill: 'rgb(235, 0, 0)'}} className="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            )}
                            {answer}
                        </li>
                    ))}
                </ul>
                {/* isCorrect */}
                <div onAnimationEnd={() => setIsCorrect(undefined)} className={`correct-message${isCorrect === true ? " correct-message--visible" : ""}`}>
                    <Icon icon="smiley" size={50} />
                    <p>Correct!</p>
                </div>
                {/* is Not Correct */}
                <div  onAnimationEnd={() => setIsCorrect(undefined)} className={`incorrect-message${isCorrect === false ? " incorrect-message--visible" : ""}`}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" className="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8"/>
                    </svg>
                    <p>Incorrect. Try again!</p>
                </div>
        </div>
    )
}
    