import {Component} from 'react';
import PropTypes from 'prop-types';

class ImageCarousel extends Component {
    static propTypes = {
        imageUrls: PropTypes.array.isRequired,
    };

    constructor(props) {
        super(props);

        this.state = {
            imageIndex: 0,
        };

        this.handlePrevClick = this.handlePrevClick.bind(this);
        this.handleNextClick = this.handleNextClick.bind(this);
    }

    handlePrevClick = () => {
        this.setState((prevState) => ({
            imageIndex: (prevState.imageIndex === 0) ? this.props.imageUrls.length - 1 : prevState.imageIndex - 1,
        }));
    };

    handleNextClick = () => {
        this.setState((prevState) => ({
            imageIndex: (prevState.imageIndex === this.props.imageUrls.length - 1) ? 0 : prevState.imageIndex + 1,
        }));
    };

    render() {
        const {imageUrls} = this.props;
        const {imageIndex} = this.state;

        // Filter out the main image from the thumbnails
        const thumbnailImages = imageUrls.filter((_, index) => index !== imageIndex);

        return (
            <div className="flex items-start justify-between gap-x-2 h-carousel" data-testid='product-gallery'>
                {/* smaller images column */}
                {thumbnailImages.length > 0 && (
                    <div className="flex flex-col space-y-2 w-1/4 h-full overflow-y-auto max-h-screen gap-y-2">
                        {thumbnailImages.map((image, index) => (
                            <img
                                key={index}
                                className="w-full h-1/4 object-contain cursor-pointer"
                                alt={`thumbnail-${index}`}
                                src={image.image_url}
                                onClick={() => this.setState({imageIndex: imageUrls.indexOf(image)})}
                            />
                        ))}
                    </div>
                )}

                {/* Main image */}
                <div className="flex flex-col w-3/4 h-full overflow-hidden relative">
                    <div className="w-full h-full">
                        <img
                            className="w-full h-full object-contain"
                            alt={`image-${imageIndex}`}
                            src={imageUrls[imageIndex].image_url}
                        />
                    </div>

                    {/* buttons to change image */}
                    {thumbnailImages.length > 0 && (
                        <div className="absolute inset-0 flex justify-between items-center px-4">
                            <button
                                className="bg-black text-white p-1 bg-opacity-50"
                                onClick={this.handlePrevClick}
                            >
                                <svg className="h-8 w-8" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2"
                                     stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z"/>
                                    <polyline points="15 6 9 12 15 18"/>
                                </svg>
                            </button>
                            <button
                                className="bg-black text-white p-1 bg-opacity-50"
                                onClick={this.handleNextClick}
                            >
                                <svg className="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                                     strokeLinecap="round" strokeLinejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </div>
                    )}
                </div>
            </div>
        );
    }
}

export default ImageCarousel;
