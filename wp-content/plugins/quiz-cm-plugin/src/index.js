import { registerBlockType } from '@wordpress/blocks';
import './index.scss';
import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow } from '@wordpress/components';
import {InspectorControls, BlockControls, AlignmentToolbar} from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import { ChromePicker } from 'react-color';

registerBlockType('quiz-cm-plugin/quiz-block', {
    title: 'Quiz Block',
    icon: 'smiley',
    category: 'text',
    attributes: {
        question: {type: 'string', default: ''},
        answers: {type: 'array', default: [undefined]},
        correctAnswerIndex: {type: 'number', default: undefined},
        bgColor: {type: 'string', default: '#ebebeb'},
        theAlignment: {type: 'string', default: 'left'},
    },
    example: {
        attributes: {
            question: 'What is the capital of France?',
            answers: ['Berlin', 'Madrid', 'Paris', 'Rome'],
            correctAnswerIndex: 2,
            bgColor: '#ebebeb',
            theAlignment: 'left',
        },
    
    },
    description: 'Make sure to mark the correct answer with the star icon. You will not be able to save the post until you do!',
    edit: EditComponent,
    save: function(props) {
        const { attributes } = props;
        return (
            <div>
                <h3>{attributes.question}</h3>
            </div>
        );
        //return null; // We'll handle rendering in PHP
    },
    
});

function EditComponent({ attributes, setAttributes }) {

    useEffect(() => {
        if (attributes.correctAnswerIndex === undefined) {
            wp.data.dispatch('core/editor').lockPostSaving('quiz-no-correct-answer');
        } else {
            wp.data.dispatch('core/editor').unlockPostSaving('quiz-no-correct-answer');
        }
    }, [attributes.correctAnswerIndex]);

    function updateQuestion(newQuestion) {
        setAttributes({ question: newQuestion });
    }
    return (
        <div className="paying-attention-block" style={{backgroundColor: attributes.bgColor}}>
            <BlockControls>
                <AlignmentToolbar value={attributes.theAlignment} onChange={(newAlign) => setAttributes({ theAlignment: newAlign })} />
             </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={true}>
                    <PanelRow>
                        <ChromePicker color={attributes.bgColor} onChangeComplete={(color) => setAttributes({ bgColor: color.hex })} disableAlpha={true}/>
                        
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <TextControl
                style={{fontSize: '20px'}}
                label="Question"
                value={attributes.question}
                onChange={updateQuestion}
            />
            <p style={{fontSize: '13px', margin: '20px 0 8px 0'}}>Answers:</p>
            {attributes.answers.map((answer, index) => {
                function newValue(newAnswer) {
                    const newAnswers = [...attributes.answers];
                    newAnswers[index] = newAnswer;
                    setAttributes({ answers: newAnswers });
                }
                function markAsCorrect(index) {
                    setAttributes({ correctAnswerIndex: index });
                }
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl autoFocus={answer === undefined}  value={answer} onChange={newValue} />
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index)} variant="link" >
                                <Icon className="mark-as-correct" icon={attributes.correctAnswerIndex === index ? 'star-filled' : 'star-empty'} />
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button variant="link" className="delete-answer-btn" onClick={() => {
                                const newAnswers = attributes.answers.filter((_, i) => i !== index);
                                // If the deleted answer is the correct one, reset correctAnswerIndex
                                if (attributes.correctAnswerIndex === index) {
                                    setAttributes({ correctAnswerIndex: undefined });       
                                }                                
                                setAttributes({ answers: newAnswers });
                            }}>
                                Delete
                            </Button>
                        </FlexItem>
                    </Flex>
                );
            })}


            <Button variant="primary" onClick={() => setAttributes({ answers: [...attributes.answers, undefined] })}>Add another answer</Button>
        </div>
    );
}