import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockType('job-offers/job-category', {
  title: __('Wyświetl oferty pracy', 'text-domain'),
  category: 'job-offers',
  attributes: {
    category: {
      type: 'string',
      default: '',
    },
  },
  edit: () => {
    return (
      <div>
        <p class="success">{__('Blok generujący widok ofert pracy został dodany pomyślnie.', 'text-domain')}</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});