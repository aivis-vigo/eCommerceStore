import {useRouteError} from "react-router-dom";

export default function ErrorPage(){
    const error = useRouteError();
    console.error(error);

    return (
        <section className="flex items-center h-screen p-16 dark:bg-gray-50 dark:text-gray-800">
            <div className="container flex flex-col items-center justify-center px-5 mx-auto my-8">
                <div className="max-w-md text-center">
                    <h2 className="mb-8 font-extrabold text-9xl dark:text-gray-400">
                        <span className="sr-only">Error</span><i>{error.statusText || error.message}</i>
                    </h2>
                    <a rel="noopener noreferrer" href="/"
                       className="px-8 py-3 font-semibold rounded dark:bg-green-600 dark:text-gray-50">Back to
                        homepage</a>
                </div>
            </div>
        </section>
    )
}