import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

registerBlockType('job-offers/job-position', {
    title: 'Stanowisko',
    icon: 'admin-site-alt3',
    category: 'job-offers',
    attributes: {
        jobTitle: {
            type: 'string',
            default: ''
        }
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        
        return (
            <Fragment>
                <TextControl
                    label="Stanowisko"
                    value={attributes.jobTitle}
                    onChange={(value) => setAttributes({ jobTitle: value })}
                />
            </Fragment>
        );
    },
    save: (props) => {
        const { attributes } = props;
        return (
            <div className="job-position">
                {attributes.jobTitle && (
                    <h2>{attributes.jobTitle}</h2>
                )}
            </div>
        );
    }
});