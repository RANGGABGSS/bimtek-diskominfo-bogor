import React, { useState, useRef, useEffect } from 'react';

export default function ResizableImage({ src, alt, initialWidth = 100, isEditable = true }) {
    const [width, setWidth] = useState(initialWidth);
    const [isResizing, setIsResizing] = useState(false);
    const containerRef = useRef(null);

    useEffect(() => {
        const handleMouseMove = (e) => {
            if (!isResizing || !containerRef.current) return;
            const rect = containerRef.current.getBoundingClientRect();
            // Calculate width based on mouse X position relative to left edge
            const newWidth = e.clientX - rect.left;
            if (newWidth > 30 && newWidth < 800) { // min/max width constraints
                setWidth(newWidth);
            }
        };

        const handleMouseUp = () => {
            setIsResizing(false);
        };

        if (isResizing) {
            window.addEventListener('mousemove', handleMouseMove);
            window.addEventListener('mouseup', handleMouseUp);
        }

        return () => {
            window.removeEventListener('mousemove', handleMouseMove);
            window.removeEventListener('mouseup', handleMouseUp);
        };
    }, [isResizing]);

    return (
        <div 
            ref={containerRef}
            className={`relative group inline-flex shrink-0 ${isResizing ? 'cursor-nwse-resize' : ''}`}
            style={{ width: `${width}px` }}
        >
            <img 
                src={src} 
                alt={alt} 
                className="w-full h-auto object-contain pointer-events-none" 
            />
            
            {isEditable && (
                <div 
                    className="absolute bottom-0 right-0 w-3.5 h-3.5 bg-blue-500/80 hover:bg-blue-600 cursor-nwse-resize opacity-0 group-hover:opacity-100 transition-opacity print:hidden z-10"
                    style={{ 
                        clipPath: 'polygon(100% 0, 100% 100%, 0 100%)',
                        bottom: '-2px',
                        right: '-2px'
                    }}
                    onMouseDown={(e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        setIsResizing(true);
                    }}
                    title="Drag to resize"
                />
            )}
        </div>
    );
}
