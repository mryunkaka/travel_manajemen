import { Head } from '@inertiajs/react';
import type { SeoMeta } from '@/types';
export default function SeoHead({ seo }: { seo: SeoMeta }) {
    return (
        <Head title={seo.title}>
            <meta
                head-key="description"
                name="description"
                content={seo.description}
            />
            <link head-key="canonical" rel="canonical" href={seo.canonical} />
            <meta head-key="robots" name="robots" content={seo.robots} />
            <meta head-key="og:locale" property="og:locale" content="id_ID" />
            <meta
                head-key="og:site_name"
                property="og:site_name"
                content={seo.site_name}
            />
            <meta head-key="og:title" property="og:title" content={seo.title} />
            <meta
                head-key="og:description"
                property="og:description"
                content={seo.description}
            />
            <meta head-key="og:type" property="og:type" content={seo.type} />
            <meta head-key="og:url" property="og:url" content={seo.canonical} />
            {seo.image && (
                <>
                    <meta
                        head-key="og:image"
                        property="og:image"
                        content={seo.image}
                    />
                    <meta
                        head-key="og:image:alt"
                        property="og:image:alt"
                        content={seo.title}
                    />
                </>
            )}
            <meta
                head-key="twitter:card"
                name="twitter:card"
                content={seo.image ? 'summary_large_image' : 'summary'}
            />
            <meta
                head-key="twitter:title"
                name="twitter:title"
                content={seo.title}
            />
            <meta
                head-key="twitter:description"
                name="twitter:description"
                content={seo.description}
            />
            {seo.image && (
                <meta
                    head-key="twitter:image"
                    name="twitter:image"
                    content={seo.image}
                />
            )}
            <script
                head-key="structured-data"
                type="application/ld+json"
                dangerouslySetInnerHTML={{
                    __html: JSON.stringify(seo.structured_data).replace(
                        /</g,
                        '\\u003c',
                    ),
                }}
            />
        </Head>
    );
}
