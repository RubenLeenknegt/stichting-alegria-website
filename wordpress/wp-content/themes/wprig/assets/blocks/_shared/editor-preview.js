export function stripHtmlText(value) {
	if (typeof value !== 'string') {
		return '';
	}

	return value
		.replace(/<[^>]+>/g, ' ')
		.replace(/&nbsp;/g, ' ')
		.replace(/&amp;/g, '&')
		.replace(/&#39;/g, "'")
		.replace(/&quot;/g, '"')
		.replace(/\s+/g, ' ')
		.trim();
}

export function PreviewCard({ title, children, style = {} }) {
	return (
		<div style={{ borderRadius: 8, border: '1px solid #e0e0e0', boxShadow: 'none', background: '#fff', ...style }}>
			{title ? (
				<div style={{ padding: '12px 16px 0' }}>
					<div style={{ fontSize: '14px', fontWeight: 600, lineHeight: 1.3 }}>{title}</div>
				</div>
			) : null}
			<div style={{ padding: title ? '12px 16px 16px' : '16px', display: 'flex', flexDirection: 'column', gap: '10px' }}>
				{children}
			</div>
		</div>
	);
}

export function PreviewField({ label, value, maxLines = 2 }) {
	const textValue = stripHtmlText(value);
	const displayValue = textValue || '—';

	return (
		<div style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
			<div style={{ fontSize: '11px', fontWeight: 600, lineHeight: 1.2, color: '#666', textTransform: 'uppercase', letterSpacing: '0.04em' }}>
				{label}
			</div>
			<div style={{
				fontSize: '13px',
				lineHeight: 1.4,
				color: '#1e1e1e',
				overflowWrap: 'anywhere',
				display: '-webkit-box',
				WebkitLineClamp: maxLines,
				WebkitBoxOrient: 'vertical',
				overflow: 'hidden'
			}}>
				{displayValue}
			</div>
		</div>
	);
}

export function PreviewThumb({ src, alt = '', size = 48 }) {
	if (!src) {
		return (
			<div style={{
				width: size,
				height: size,
				display: 'flex',
				alignItems: 'center',
				justifyContent: 'center',
				borderRadius: 4,
				border: '1px dashed #c7c7c7',
				background: '#f7f7f7',
				color: '#666',
				fontSize: '11px',
				textAlign: 'center',
				padding: '4px'
			}}>
				No image
			</div>
		);
	}

	return (
		<img
			src={src}
			alt={alt}
			style={{
				display: 'block',
				width: size,
				height: size,
				objectFit: 'cover',
				borderRadius: 4,
				border: '1px solid #e0e0e0',
				background: '#f7f7f7'
			}}
		/>
	);
}

export function PreviewEmptyState({ title, message }) {
	return (
		<div style={{ padding: '12px 14px', border: '1px solid #d7e2f0', borderRadius: 6, background: '#f8fbff', color: '#1e1e1e' }}>
			{title ? <strong>{title}</strong> : null}
			{message ? <div style={{ marginTop: '4px', fontSize: '13px', lineHeight: 1.4 }}>{message}</div> : null}
		</div>
	);
}
