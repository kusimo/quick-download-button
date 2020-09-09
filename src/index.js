import { registerBlockType } from '@wordpress/blocks';
import { SVG, Path } from '@wordpress/primitives';
import { __ } from '@wordpress/i18n';
import { RichText, MediaUpload } from '@wordpress/block-editor';
import { Button, IconButton } from '@wordpress/components'


const download = (
	<SVG xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<Path d="M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z" />
	</SVG>
);

 
registerBlockType( 'custom-download/download-button', {
    title: __('Download Button','custom-download'),
    icon: download,
    category: 'media',
    attributes: {
        downloadTitle : {
            type: 'string',
            source: 'text',
            selector: 'button'
        },
        downloadUrl: {
            type: 'string',
            source: 'attribute',
            selector: 'form',
            attribute: 'action',
            default: '/'
        },
        downloadFileSize: {
            type: "string",
            source: "text",
            selector: "p.down",
            default: "File size"
          },
          downloadId: {
            type: 'string',
            source: 'attribute',
            selector: '.custom-download-button-inner',
            attribute: 'id'
        }

    },

    edit: props => {

        // Props parameter holds all the info.
        //console.info(props);

        // Lift info from props and populate various constants.
        const {
            attributes : {downloadTitle, downloadUrl, downloadFileSize, downloadId},
            setAttributes,
            className
        } = props;

        const onChangeTitle = (newTitle) => {
            setAttributes( { downloadTitle: newTitle })
        };

        const onMediaSelect = uploadObject => {
            console.info('Media Info: ', uploadObject);
            setAttributes({ downloadUrl: uploadObject.url });
            setAttributes({ downloadFileSize: uploadObject.filesizeHumanReadable });
            setAttributes({ downloadId: uploadObject.id });
          }

          let downloadExt = downloadUrl.substr(downloadUrl.lastIndexOf('.') + 1);
          downloadExt = downloadExt.trim();
          console.log('ID: ', downloadId);
          //const extensionArray = ['pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt'];
          const imageExtension = ['jpg','jpeg','tiff','png','bmp','gif'];
          const foundExt = imageExtension.includes(downloadExt.toLowerCase());
          //Image
          if(foundExt === true) {
            downloadExt = 'image';
          } 
         
         

          const handleSubmit = (event) => {
            event.preventDefault();
          }

      

        return (
            <div className= {`${className} button--download`}>
                <div className="custom-download-button-inner" id={downloadId}>
                    <form method="post" onSubmit={handleSubmit} >
                        <button className="g-btn f-l bsbtn d-block position-relative shadow rounded-lg border-0 download-btn-title" type="submit"  title="Download" formtarget="_blank">
                        <RichText 
                            placeholder={__("Download", "custom-download")}
                            onChange= { onChangeTitle}
                            value= {downloadTitle}
                            />
                        </button>
                    
                    <p className="up"><i className={`fi fi-${downloadExt}`}></i> 
                    <MediaUpload 
                        onSelect={onMediaSelect}
                        value={props.attributes.downloadUrl}
                        render={({ open }) => (
                            <IconButton
                              className="custom-download-logo__button"
                              onClick={open}
                              icon={download}
                              showTooltip="true"
                              label={__("Upload File.", "custom-download")}
                            /> 
                          )}
                    />
                    </p>
                    <p className="down"><i className="fi-folder-o"></i>
                    {downloadFileSize} 
                    </p>
                    </form>
                </div>
            </div>
        )
       
    },
    save: props =>  {
        let downloadExt = props.attributes.downloadUrl.substr(props.attributes.downloadUrl.lastIndexOf('.') + 1);
            //const extensionArray = ['pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt'];
          const imageExtension = ['jpg','jpeg','tiff','png','bmp','gif'];
          const foundExt = imageExtension.includes(downloadExt.toLowerCase());
          if(foundExt === true) {
            downloadExt = 'image';
          } 

          const {
            attributes: { downloadId }
          } = props;

        return (
            <div className="button--download">
                 <div className="custom-download-button-inner" id={downloadId}>
                    <form method="post" action={props.attributes.downloadUrl}>
                        <button className="g-btn f-l bsbtn d-block position-relative shadow rounded-lg border-0 download-btn-title" type="submit"  title="Download" formtarget="_blank">
                            <RichText.Content value={props.attributes.downloadTitle} />
                        </button>
                    
                    <p className="up"><i className={`fi fi-${downloadExt}`}></i> 
                    </p>
                    <p className="down"><i className="fi-folder-o"></i>
                    {props.attributes.downloadFileSize}
                    </p>
                    </form>
                </div>
            </div>
        )
    },
} );