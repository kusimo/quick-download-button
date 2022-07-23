import { registerBlockType } from '@wordpress/blocks'; 
import { SVG, Path } from '@wordpress/primitives';
import { __ } from '@wordpress/i18n';
import {ColorPalette, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl, ToggleControl, RadioControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';

const download_button_icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<Path d="M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z" />
	</SVG>
);



registerBlockType( 'quick-download-button/download-button', {
    title: __('Download Button','quick-download-button'),
    icon: download_button_icon,
    description: __('Use download button for your file download link.', 'quick-download-button'),
    category: 'widgets',
    keywords: [
        __('download', 'quick-download-button'),
        __('button', 'quick-download-button'),
    ],
    attributes: {
        downloadTitle : {
            type: 'string',
            source: 'text',
            selector: 'button',
            default: __('Download', 'quick-download-button')
        },
        downloadTitlePlaceholder : {
            type: 'string',
            source: 'attribute', 
            selector: 'button',
            attribute: 'title',
            default: __('Download', 'quick-download-button')
        },
        downloadPageId: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-page-id',
            default: qdbu_data.download_page_id
        },
        downloadAttachmentId: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-attachment-id'
        },
        downloadFormat: {
            type: 'string',
            source: 'attribute',
            selector: 'p.up i',
            attribute: 'class',
            default: 'fi fi-file'
        },
        downloadFileSize: {
            type: "string",
            source: "text",
            selector: "p.down",
            default: __('File size', 'quick-download-button')
          },
        downloadWaitTime: {
            type: "string",
            source: 'attribute',
            selector: 'button',
            attribute: 'data-wait-time',
            default: 0
        },
        externalUrl: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-external-url',
        }, 
        waitDuration: {
            type: 'string',
            source: 'attritube',
            selector: 'button',
            attribute: 'data-wait-duration',
            default: "0"
        },
        waitMessage: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-msg',
        },
        spinnerValue: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-spinner',
        },
        useExternalLink: {
            type: Boolean,
            source: 'attribute',
            selector: 'button',
            attribute: 'ext',
            default: false
        },
        backgroundColor: {
            type: "string"
        },
        haveExternal: {
            type: "boolean"
        },
        pID: {
            type: 'string',
            source: 'attribute',
            selector: 'button',
            attribute: 'data-id', 
        }


    },

    edit: props => {


        // Lift info from props and populate various constants.
        const {
            attributes : {  
                downloadTitle, 
                downloadFileSize, 
                downloadPageId, 
                downloadAttachmentId, 
                downloadFormat, 
                downloadTitlePlaceholder, 
                haveExternal,
                externalUrl,
                waitDuration,
                waitMessage,
                spinnerValue,
                backgroundColor,
                pID
            },
            setAttributes,
            className
        } = props;

        const onChangeTitle = (newTitle) => {
            setAttributes( { downloadTitle: newTitle } );
            setAttributes ( {downloadTitlePlaceholder : newTitle} );
            setAttributes( { downloadWaitTime : '0' } );
            if("" !== postId && null !== postId) {
                setAttributes( { pID : postId } );
            }
            
        };

  

    
        const onMediaSelect = uploadObject => {
            //console.info('Media Info: ', uploadObject);
            setAttributes({ downloadFileSize: uploadObject.filesizeHumanReadable });
            let aid = parseInt(uploadObject.id)+parseInt(downloadPageId);
            let attachementUrl = uploadObject.url;
            setAttributes({ downloadAttachmentId: aid });
            setAttributes({ downloadPageId: qdbu_data.download_page_id }); 

            let fileExt = uploadObject.url.substr(uploadObject.url.lastIndexOf('.') + 1).trim();

            //Check if ext is image
            let imageExtension = ['jpg','jpeg','tiff','png','bmp','gif'];
            let foundExt = imageExtension.includes(fileExt.toLowerCase());

            if(foundExt === true) {
                let downloadExt = 'fi fi-image';
                setAttributes({ downloadFormat: downloadExt });
            } 

            //Check for other files
            let otherExtensions = ['pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt','csv'];
            let foundOthers = otherExtensions.includes(fileExt.toLowerCase());

            if(foundOthers === true) {
                let extIndex = otherExtensions.indexOf(fileExt);
               
                let ext = otherExtensions[`${extIndex}`];

                let downloadExt = 'fi fi-'+ext;

                setAttributes({ downloadFormat: downloadExt });

            }

          }

          // Set newBackgroundColor
          const onChangeBackgroundColor = newBackgroundColor => {
              setAttributes( { backgroundColor: newBackgroundColor });
          }

          const isUrl = string => {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
            return !!pattern.test(string);
         }
          const onChangeToggle = newValue => {
              setAttributes( { haveExternal: newValue });
              if(haveExternal === false) {
                  // reset url
                  setAttributes( {externalUrl: '' } ) 
              }
          }

          const onUrlChange = newValue => {
           setAttributes( {externalUrl: newValue } ) 
           if(isUrl(externalUrl)) {
             updateMetaValue(externalUrl);
            }
          }

          const onMsgChange = newValue => {
              setAttributes(
                 { waitMessage: newValue } 
              )
          }

          const onRadioChange = newValue => {
            setAttributes(
               { spinnerValue: newValue } 
            )
        }

          const handleSubmit = (event) => {
            event.preventDefault();
          }

          
          const extUrl =  haveExternal
          ?
              <TextControl
              label={  __(`${isUrl(externalUrl) ? 'Enter URL (Url is valid)': 'Enter URL (*Provide a valid URL)'}`, 'quick-download-button') }
              help={ __( 'Don\'t use external URL if the file is located on your site. Double click on the download icon to upload file.', "quick-download-button") }
              value={ externalUrl }
              onChange={ 
                onUrlChange
              }
          /> :
          '';

          const durationnMsg = parseInt(spinnerValue)  > 0 ? 
          <TextControl
              label={  __(`Message to the user`, 'quick-download-button') }
              value={ waitMessage }
              placeholder={__("Please wait...", "quick-download-button")}
              onChange={ 
                onMsgChange
              }
          /> :
          '';

        return [
            <InspectorControls>
                <PanelBody title= { __( 'Color settings', "quick-download-button") }>
                    <div className="components-base-control">
                        <div className="component-base-control__field">
                            <label className="components-base-control__label qdbu-editor-label">
                                { __("Background color", "quick-download-button")}
                            </label>
                            <ColorPalette
                                value={props.backgroundColor}
                                onChange={onChangeBackgroundColor}
                             />
                        </div>
                    </div>

                </PanelBody>
                <PanelBody title= { __( 'URL settings', "quick-download-button") }>
                <div className="components-base-control">
                    <div className="component-base-control__field">

                    <ToggleControl
                        label= { __( 'External URL', "quick-download-button") } //"External URL"
                        help={
                            haveExternal
                                ?  __( 'Use external URL.', "quick-download-button") //'Use external URL.'
                                : __( 'Do not use External URL. Please double click the download icon to upload file from your site.', "quick-download-button") //'Do not use External URL.'
                        }
                        checked={ haveExternal }
                        onChange={ onChangeToggle }
                    />
                      { extUrl }
                    </div>
                </div>
                </PanelBody>
                <PanelBody title= { __( 'Countdown settings', "quick-download-button") }>
                <div className="components-base-control">
                    <div className="component-base-control__field qdbu-label-mtop">
                    <RadioControl
                        label={ __( 'Timer', "quick-download-button") }
                        help={ __( 'The amount of time in seconds you want the user to wait before the download begins. Default is 0.', "quick-download-button") }
                        selected={ spinnerValue }
                        options={ [
                            { label: '0', value: '0' },
                            { label: '10', value: '10' },
                            { label: '20', value: '20' },
                            { label: '30', value: '30' },
                        ] }
                        onChange={ onRadioChange }
                    />
                    { durationnMsg }
                    </div>
                </div>
                </PanelBody>
            </InspectorControls>,
            <div className= {`${className} button--download`}>
            <div className={`${haveExternal ? 'custom-download-button-inner ext-link': 'custom-download-button-inner'}`}>
                    <button className="g-btn f-l position-relative shadow" 
                    type="button" title={downloadTitlePlaceholder} 
                    data-attachment-id={downloadAttachmentId} 
                    data-page-id={downloadPageId}
                    data-post-id=""
                    data-have-external={haveExternal}
                    data-external-url={externalUrl}
                    data-wait-duration={waitDuration}
                    data-spinner={spinnerValue}
                    data-msg={waitMessage}
                    data-id={pID}
                    onSubmit={handleSubmit}>
                    <RichText 
                        placeholder={__("Download", "quick-download-button")}
                        onChange= { onChangeTitle}
                        value= {downloadTitle}
                        />
                    </button>
                
                <p className="up" style={{background: backgroundColor}} ><i className={downloadFormat}></i> 
                <MediaUpload 
                    onSelect={onMediaSelect}
                    value={props.attributes.downloadUrl}
                    render={({ open }) => (
                        <Button
                          className="custom-download-logo__button"
                          onClick={open}
                          icon={download_button_icon}
                          showTooltip="true"
                          label={__("Upload File.", "quick-download-button")}
                        /> 
                      )}
                />
                </p>
                <p className="down" style={{background: backgroundColor}}><i className="fi-folder-o"></i>
                {downloadFileSize} 
                </p>
            </div>
        </div>

        ]


       
    },
    save: props =>  {
      
          const { attributes } = props;          
          
        return (
            <div className="button--download">
                 <div className={`${attributes.haveExternal ? 'custom-download-button-inner ext-link': 'custom-download-button-inner'}`}>
                        <button className="g-btn f-l position-relative shadow" type="button" 
                        data-attachment-id={attributes.downloadAttachmentId} 
                        data-page-id={attributes.downloadPageId}
                        data-post-id=""
                        data-have-external={attributes.haveExternal}
                        data-external-url={attributes.externalUrl}
                        data-wait-duration={props.attributes.waitDuration}
                        data-msg={attributes.waitMessage}
                        data-spinner={attributes.spinnerValue}
                        data-id={attributes.pID}
                        title={attributes.downloadTitlePlaceholder}>
                            <RichText.Content value={attributes.downloadTitle} />
                        </button>
                    
                    <p className="up" style={{background: attributes.backgroundColor}} ><i className={attributes.downloadFormat}></i> 
                    </p>
                    <p className="down" style={{background: attributes.backgroundColor}}><i className="fi-folder-o"></i>
                    {props.attributes.downloadFileSize}
                    </p>
                </div>
                
            </div>
        )
    },
} );